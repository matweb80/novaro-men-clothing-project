<script setup>
import { provide, ref, onMounted, onUnmounted, computed } from "vue";
import NavLevel from "./NavLevel.vue";

const openPath = ref([]);
const categories = ref([]);

const isOpenCategory = ref(false);

provide("desktopNavOpenPath", openPath);

// Any click outside a nav button (buttons stop propagation on click) closes
// every open flyout; Escape does the same — mirrors the prototype's
// document-level listeners.
function onDocumentClick() {
  openPath.value = [];
}
function onDocumentKeydown(e) {
  if (e.key === "Escape") openPath.value = [];
}

function isRoute(path) {
  window.location.href = path;
}

const path = computed(function () {
  return window.location.pathname;
});

const productPath = computed(() => window.location.href.includes("/products/"));

onMounted(async () => {
  document.addEventListener("click", onDocumentClick);
  document.addEventListener("keydown", onDocumentKeydown);

  try {
    const response = await fetch("/api/categories");
    if (response.ok) categories.value = await response.json();
  } catch (error) {
    console.error("Unable to load categories", error);
  }
});
onUnmounted(() => {
  document.removeEventListener("click", onDocumentClick);
  document.removeEventListener("keydown", onDocumentKeydown);
});
</script>

<template>
  <nav class="primary-nav" aria-label="دسته‌بندی‌های اصلی">
    <ul class="nav-level" role="list" data-depth="-1">
      <li class="nav-item">
        <a
          :aria-current="path === '/' ? 'page' : undefined"
          class="nav-link"
          :class="{ active: path === '/' }"
          @click="isRoute('/')"
          href="/"
        >
          {{ $t("Home") }}
        </a>
      </li>
      <li class="nav-item">
        <a
          :aria-current="
            path === '/products' || productPath ? 'page' : undefined
          "
          class="nav-link"
          :class="{ active: path === '/products' || productPath }"
          @click="isRoute('/products')"
          href="/products"
        >
          {{ $t("Shop") }}
        </a>
      </li>
      <li
        class="nav-item"
        @mouseenter="isOpenCategory = true"
        @mouseleave="isOpenCategory = false"
      >
        <button
          type="button"
          class="nav-link"
          :class="{ 'is-open-category': isOpenCategory }"
        >
          <span>دسته بندی ها</span>
          <font-awesome-icon
            icon="fa-solid fa-chevron-left"
            aria-hidden="true"
            class="chev"
          />
        </button>
        <transition name="categories" mode="out-in">
          <div v-if="isOpenCategory" class="nav-category">
            <NavLevel :items="categories" :depth="0" :path="[]" />
          </div>
        </transition>
      </li>
      <li class="nav-item" v-if="!isTablet">
        <a
          :aria-current="path === '/contact-us' ? 'page' : undefined"
          class="nav-link"
          :class="{ active: path === '/contact-us' }"
          @click="isRoute('/contact-us')"
          href="/contact-us"
        >
          {{ $t("Contact Us") }}
        </a>
      </li>
      <li class="nav-item">
        <a
          :aria-current="path === '/about-us' ? 'page' : undefined"
          class="nav-link"
          :class="{ active: path === '/about-us' }"
          @click="isRoute('/about-us')"
          href="/about-us"
        >
          {{ $t("About Us") }}
        </a>
      </li>
    </ul>
  </nav>
</template>

<style scoped>
.is-open-category > .chev {
  transform: rotate(-90deg);
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