<script setup>
import { ref, nextTick, onMounted, computed } from "vue";
import { CATEGORY_TREE } from "@/data/categories";
import { useBodyScrollLock } from "@/composables/useBodyScrollLock";
import { useFocusTrap } from "@/composables/useFocusTrap";

const ROOT_TITLE = "منو";
const categories = ref([]);

const isOpen = ref(false);
const isOpenCategory = ref(false);
const stack = ref([{ title: ROOT_TITLE, items: categories.value }]);

const navEl = ref(null);
const panelEl = ref(null);
const closeBtnEl = ref(null);
const backBtnEl = ref(null);
const triggerEl = ref(null);

const { lock, unlock } = useBodyScrollLock();
const { trap, releaseTrap } = useFocusTrap();

onMounted(async () => {
  try {
    const response = await fetch("/api/categories");
    if (response.ok) categories.value = await response.json();
  } catch (error) {
    console.error("Unable to load categories", error);
  }
});

const currentLevel = () => stack.value[stack.value.length - 1];

function hasChildren(node) {
  return !!(node.child && node.child.length);
}

function open(triggerButton) {
  triggerEl.value = triggerButton || null;
  stack.value = [{ title: ROOT_TITLE, items: categories.value }];
  isOpen.value = true;
  lock();
  nextTick(() => {
    trap(navEl.value);
    closeBtnEl.value?.focus();
  });
}

function close() {
  isOpen.value = false;
  unlock();
  releaseTrap();
  triggerEl.value?.focus();
}

function pushLevel(node) {
  stack.value.push({ title: node.name, items: node.child });
  nextTick(() => {
    if (panelEl.value) panelEl.value.scrollTop = 0;
    backBtnEl.value?.focus();
  });
}

function popLevel() {
  if (stack.value.length > 1) stack.value.pop();
}

function onKeydown(e) {
  if (e.key === "Escape") close();
}

function isRoute(path) {
  window.location.href = path;
}

const path = computed(function () {
  return window.location.pathname;
});

const productPath = computed(() => window.location.href.includes("/products/"));

defineExpose({ open, close });
</script>

<template>
  <div
    class="mobile-nav-backdrop"
    :class="{ 'is-visible': isOpen }"
    @click="close"
  />
  <div
    ref="navEl"
    class="mobile-nav"
    :class="{ 'is-open': isOpen, 'has-parent': stack.length > 1 }"
    role="dialog"
    aria-modal="true"
    aria-label="منوی دسته‌بندی‌ها"
    @keydown="onKeydown"
  >
    <div class="mobile-nav__header">
      <button
        v-if="stack.length > 1"
        ref="backBtnEl"
        type="button"
        class="mobile-nav__back"
        @click="popLevel"
      >
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M15 5l-7 7 7 7" />
        </svg>
        بازگشت
      </button>
      <p class="mobile-nav__title">{{ currentLevel().title }}</p>
      <button
        ref="closeBtnEl"
        type="button"
        class="icon-btn"
        aria-label="بستن منو"
        @click="close"
      >
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <line x1="6" y1="6" x2="18" y2="18" />
          <line x1="18" y1="6" x2="6" y2="18" />
        </svg>
      </button>
    </div>
    <nav
      ref="panelEl"
      class="mobile-nav__panel"
      aria-label="سطح فعلی دسته‌بندی"
    >
      <a
        :aria-current="path === '/' ? 'page' : undefined"
        class="mobile-nav__row"
        :class="{ active: path === '/' }"
        @click="isRoute('/')"
        href="/"
      >
        {{ $t("Home") }}
      </a>
      <a
        :aria-current="path === '/products' || productPath ? 'page' : undefined"
        class="mobile-nav__row"
        :class="{ active: path === '/products' || productPath }"
        @click="isRoute('/products')"
        href="/products"
      >
        {{ $t("Shop") }}
      </a>
      <button
        type="button"
        class="mobile-nav__row"
        :class="{ 'is-open-category': isOpenCategory }"
        @click="isOpenCategory = !isOpenCategory"
      >
        <span>دسته بندی ها</span>
        <svg class="chev" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M8 5l7 7-7 7" />
        </svg>
      </button>
      <transition name="categories" mode="out-in">
        <div v-if="isOpenCategory" class="mobile-nav-category">
          <template v-for="node in currentLevel().items" :key="node.id">
            <button
              v-if="hasChildren(node)"
              type="button"
              class="mobile-nav__row"
              @click="pushLevel(node)"
            >
              <span>{{ node.name }}</span>
              <svg class="chev" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M8 5l7 7-7 7" />
              </svg>
            </button>
            <a
              v-else
              :href="`/categories/${node.slug}`"
              class="mobile-nav__row"
            >
              <span>{{ node.name }}</span>
            </a>
          </template>
        </div>
      </transition>
      <a
        :aria-current="path === '/contact-us' ? 'page' : undefined"
        class="mobile-nav__row"
        :class="{ active: path === '/contact-us' }"
        @click="isRoute('/contact-us')"
        href="/contact-us"
      >
        {{ $t("Contact Us") }}
      </a>
      <a
        :aria-current="path === '/about-us' ? 'page' : undefined"
        class="mobile-nav__row"
        :class="{ active: path === '/about-us' }"
        @click="isRoute('/about-us')"
        href="/about-us"
      >
        {{ $t("About Us") }}
      </a>
    </nav>
  </div>
</template>

<style scoped>
.is-open-category > .chev {
  transform: rotate(90deg);
}
.mobile-nav-category {
  border: 1px solid var(--c-border);
  border-radius: var(--r-md);
  margin: 0 8px;
}

.categories-enter-from,
.categories-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.categories-enter-active {
  transition: all 0.3s ease-out;
}

.categories-leave-active {
  transition: all 0.3s ease-in;
}

.categories-enter-to,
.categories-leave-from {
  opacity: 1;
  transform: translateY(0);
}
</style>
