<script setup>
import { ref, nextTick, onMounted, computed } from "vue";
import { CATEGORY_TREE } from "@/data/categories";
import { PRODUCTS } from "@/data/products";
import {
  RECENT_SEARCHES,
  SUGGESTED_CATEGORY_IDS,
  SUGGESTED_CATEGORY_SLUGS,
  TRENDING_PRODUCT_IDS,
} from "@/data/search";
import { findCategoryBySlug } from "@/utils/category";
import { formatToman } from "@/utils/formatPrice";
import { useSearch } from "@/composables/useSearch";
import { useFilteredProducts } from "@/composables/useFilteredProducts";
import { useBodyScrollLock } from "@/composables/useBodyScrollLock";
import { useFocusTrap } from "@/composables/useFocusTrap";

const isOpen = ref(false);
const overlayEl = ref(null);
const inputEl = ref(null);
const triggerEl = ref(null);

const categories = ref([]);

const {
  query,
  status,
  matchedCategories,
  matchedProducts,
  matchedTags,
  liveMessage,
  runSearch,
  reset,
} = useSearch();
const { products, getFilters, loadFiltersFromUrl, applyFilters, currentPage } =
  useFilteredProducts();
const { lock, unlock } = useBodyScrollLock();
const { trap, releaseTrap } = useFocusTrap();

onMounted(async () => {
  // Load Categories
  try {
    const response = await fetch("/api/categories");
    if (response.ok) categories.value = await response.json();
  } catch (error) {
    console.error("Unable to load categories", error);
  }
  // Load Products
  loadFiltersFromUrl();
  await applyFilters(currentPage.value, false);
});

const suggestedCategories = computed(() => {
  return SUGGESTED_CATEGORY_SLUGS.map((slug) =>
    findCategoryBySlug(categories.value, slug)
  ).filter(Boolean);
});
const trendingProducts = computed(() => {
  return TRENDING_PRODUCT_IDS.map((id) =>
    products.value.find((p) => p.id === id)
  ).filter(Boolean);
});

const categoryPath = (tree, path = []) => {
  let out = [];
  tree.forEach((node) => {
    const fullPath = path.concat([node.name]);
    out.push({ id: node.id, name: node.name, path: fullPath.join(" / ") });
    if (node.child && node.child.length) {
      out = out.concat(categoryPath(node.child, fullPath));
    }
  });
  return out;
};

function open(triggerButton) {
  triggerEl.value = triggerButton || null;
  isOpen.value = true;
  reset();
  lock();
  nextTick(() => {
    trap(overlayEl.value);
    inputEl.value?.focus();
  });
}

function close() {
  isOpen.value = false;
  unlock();
  releaseTrap();
  triggerEl.value?.focus();
}

function onInput(e) {
  runSearch(e.target.value);
}

function pickChip(name) {
  query.value = name;
  runSearch(name);
  inputEl.value?.focus();
}

function onKeydown(e) {
  if (e.key === "Escape") close();
}

defineExpose({ open, close, isOpen });
</script>

<template>
  <div
    ref="overlayEl"
    class="search-overlay"
    :class="{ 'is-open': isOpen }"
    role="dialog"
    aria-modal="true"
    aria-label="جستجو در نوواررو"
    @keydown="onKeydown"
  >
    <div class="search-overlay__bar">
      <div class="search-overlay__field">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <circle cx="11" cy="11" r="7" />
          <line x1="21" y1="21" x2="16.5" y2="16.5" />
        </svg>
        <input
          ref="inputEl"
          type="text"
          name="search"
          class="search-overlay__input"
          placeholder="جستجوی محصول، دسته‌بندی…"
          autocomplete="off"
          v-model="query"
          @input="runSearch"
        />
      </div>
      <button
        type="button"
        class="icon-btn search-overlay__close"
        aria-label="بستن جستجو"
        @click="close"
      >
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <line x1="6" y1="6" x2="18" y2="18" />
          <line x1="18" y1="6" x2="6" y2="18" />
        </svg>
      </button>
    </div>

    <div class="search-overlay__body">
      <!-- IDLE: recent searches + suggested categories + trending products -->
      <template v-if="status === 'idle'">
        <div class="search-section">
          <p class="search-section__title">جستجوهای اخیر</p>
          <div class="recent-list">
            <button
              v-for="term in RECENT_SEARCHES"
              :key="term"
              type="button"
              class="recent-item"
              @click="pickChip(term)"
            >
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="9" />
                <path d="M12 7v5l3 3" />
              </svg>
              <span>{{ term }}</span>
            </button>
          </div>
        </div>

        <div class="search-section">
          <p class="search-section__title">دسته‌بندی‌های پیشنهادی</p>
          <div class="search-chips">
            <button
              v-for="cat in suggestedCategories"
              :key="cat.id"
              type="button"
              class="search-chip"
              @click="pickChip(cat.name)"
            >
              {{ cat.name }}
            </button>
          </div>
        </div>

        <div class="search-section">
          <p class="search-section__title">پیشنهاد ما</p>
          <div class="search-results-list">
            <a
              v-for="p in trendingProducts"
              :key="p.id"
              :href="'/products/' + p.slug"
              class="search-result-row"
            >
              <span>{{ p.title }}</span>
              <span
                class="search-result-row__meta latin"
                style="direction: rtl"
                >{{ formatToman(p.is_special ? p.sale_price : p.price) }}</span
              >
            </a>
          </div>
        </div>
      </template>

      <!-- LOADING -->
      <div v-else-if="status === 'loading'" class="search-loading">
        <span class="spinner" aria-hidden="true" />
        <span>در حال جستجو…</span>
      </div>

      <!-- RESULTS / EMPTY -->
      <template v-else-if="status === 'results'">
        <div
          v-if="!matchedCategories.length && !matchedProducts.length"
          class="search-empty"
        >
          <p>نتیجه‌ای برای «{{ query }}» پیدا نشد.</p>
        </div>
        <div
          v-if="!matchedCategories.length && !matchedProducts.length"
          class="search-section"
        >
          <p class="search-section__title">شاید این دسته‌ها مدنظرتان باشد</p>
          <div class="search-chips">
            <button
              v-for="cat in suggestedCategories"
              :key="cat.id"
              type="button"
              class="search-chip"
              @click="pickChip(cat.name)"
            >
              {{ cat.name }}
            </button>
          </div>
        </div>

        <div v-if="matchedProducts.length" class="search-section">
          <p class="search-section__title">محصولات</p>
          <div class="search-results-list">
            <a
              v-for="p in matchedProducts"
              :key="p.id"
              :href="'/products/' + p.slug"
              class="search-result-row"
            >
              <span>{{ p.title }}</span>
              <span
                class="search-result-row__meta latin"
                style="direction: rtl"
                >{{ formatToman(p.is_special ? p.sale_price : p.price) }}</span
              >
            </a>
          </div>
        </div>

        <div v-if="matchedCategories.length" class="search-section">
          <p class="search-section__title">دسته‌بندی‌ها</p>
          <div class="search-results-list">
            <a
              v-for="c in categoryPath(matchedCategories)"
              :key="c.id"
              :href="'/categories/' + c.path"
              class="search-result-row"
            >
              <span>{{ c.name }}</span>
              <span class="search-result-row__meta">{{ c.path }}</span>
            </a>
          </div>
        </div>

        <div v-if="matchedTags.length" class="search-section">
          <p class="search-section__title">برچسب‌ها</p>
          <div class="search-results-list">
            <a
              v-for="t in matchedTags"
              :key="t.id"
              :href="'/tags/' + t.slug"
              class="search-result-row"
            >
              <span>{{ t.name }}</span>
            </a>
          </div>
        </div>
      </template>
    </div>

    <div class="visually-hidden" aria-live="polite">{{ liveMessage }}</div>
  </div>
</template>
