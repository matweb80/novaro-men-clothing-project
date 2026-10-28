<script setup>
import { ref, computed, onMounted, onUnmounted, provide } from "vue";
import { useFilteredProducts } from "@/composables/useFilteredProducts";
import ShopBreadcrumb from "@/components/shop/ShopBreadcrumb.vue";
import ShopCategoryHeader from "@/components/shop/ShopCategoryHeader.vue";
import ShopToolbar from "@/components/shop/ShopToolbar.vue";
import FilterPanel from "@/components/shop/FilterPanel.vue";
import FilterDrawer from "@/components/shop/FilterDrawer.vue";
import Pagination from "@/components/shop/Pagination.vue";
import ShopEmptyState from "@/components/shop/ShopEmptyState.vue";
import ProductGrid from "@/components/product/ProductGrid.vue";
import { useMediaQuery } from "../composables/useMediaQuery";

const {
  getFilters,
  loadFiltersFromUrl,
  applyFilters,
  resetFilters,
  fetchCategoryProducts,
  category,
  fetchTagProducts,
  tag,
  attributes,
  products,
  categoryProducts,
  tagProducts,
  totalResults,
  priceRange,
  currentPage,
  lastPage,
  isLoading,
  filters,
  changePage,
  visiblePages,
  activeFilterCount,
  sort,
  setSort,
} = useFilteredProducts();

const props = defineProps({
  categorySlug: { type: String, required: false },
  tagSlug: { type: String, required: false },
});

// هنگام لود صفحه
onMounted(async () => {
  loadFiltersFromUrl();
  await getFilters();
  if (props.categorySlug)
    await fetchCategoryProducts(currentPage.value, false, props.categorySlug);
  else if (props.tagSlug)
    await fetchTagProducts(currentPage.value, false, props.tagSlug);
  else await applyFilters(currentPage.value, false);
});

// بازخوانی هنگام Back / Forward مرورگر
window.addEventListener("popstate", () => {
  loadFiltersFromUrl();
  applyFilters(currentPage.value, false);
});

// The filter panel is a single shared instance, relocated (not duplicated)
// between the desktop sidebar and the mobile drawer depending on
// viewport — matching the prototype's placeFilterPanel(), implemented here
// with <Teleport> instead of manual DOM node moves.
const isDesktop = ref(useMediaQuery("(min-width: 992px)"));

function onResize() {
  isDesktop.value = window.matchMedia("(min-width: 992px)").matches;
}
onMounted(() => window.addEventListener("resize", onResize));
onUnmounted(() => window.removeEventListener("resize", onResize));

const filterDrawerRef = ref(null);

function openFilterDrawer(triggerButton) {
  filterDrawerRef.value?.open(triggerButton);
}

const searchHandle = async (e) => {
  if (props.categorySlug)
    await fetchCategoryProducts(currentPage.value, true, props.categorySlug);
  else if (props.tagSlug)
    await fetchTagProducts(currentPage.value, true, props.tagSlug);
};

provide("getFilters", getFilters);
provide("loadFiltersFromUrl", loadFiltersFromUrl);
provide("applyFilters", applyFilters);
provide("resetFilters", resetFilters);
provide("attributes", attributes);
provide("products", products);
provide("totalResults", totalResults);
provide("priceRange", priceRange);
provide("currentPage", currentPage);
provide("lastPage", lastPage);
provide("isLoading", isLoading);
provide("filters", filters);
provide("changePage", changePage);
provide("visiblePages", visiblePages);
provide("sort", sort);
provide("setSort", setSort);
provide("activeFilterCount", activeFilterCount);
</script>

<template>
  <main id="main">
    <div class="container shop-top">
      <shop-breadcrumb />
    </div>

    <ShopCategoryHeader
      v-if="categorySlug"
      :category-node="category"
      :product-count="categoryProducts.total"
    />
    <ShopCategoryHeader
      v-if="tagSlug"
      :category-node="tag"
      :product-count="tagProducts.total"
    />

    <div class="shop-layout container">
      <!-- Desktop filter sidebar. Its content is the single shared filter
           instance — on mobile the same instance is teleported into the
           drawer below rather than duplicated, so filter markup/state
           exists exactly once. -->
      <aside
        id="filterSidebarTarget"
        class="filter-sidebar"
        aria-label="فیلتر محصولات"
        v-if="!categorySlug && !tagSlug"
      />

      <div class="shop-main">
        <ShopToolbar
          :count="
            products.length || categoryProducts.total || tagProducts.total
          "
          :total-count="totalResults"
          @open-filters="openFilterDrawer"
          :class="{ 'category-tag-shop-toolbar': categorySlug || tagSlug }"
        />

        <!-- Search -->
        <div
          class="filter-section filter-search"
          v-if="categorySlug || tagSlug"
        >
          <div class="filter-search__input">
            <font-awesome-icon
              icon="fa-solid fa-magnifying-glass"
              class="filter-search__icon"
            />

            <input
              id="category-product-filter-search"
              v-model="filters.search"
              type="text"
              name="search"
              autocomplete="off"
              placeholder="جستجو در محصولات..."
              @input="searchHandle($event)"
            />
          </div>
        </div>

        <div class="product-grid-wrap">
          <ProductGrid
            v-if="
              !isLoading &&
              (categoryProducts.data?.length > 0 ||
                tagProducts.data?.length > 0 ||
                products.length > 0)
            "
            :products="categoryProducts?.data || tagProducts?.data || products"
            :class="{ 'category-tag-product-grid': categorySlug || tagSlug }"
          />
          <!-- 🌀 Loader -->
          <div v-if="isLoading">
            <base-spinner></base-spinner>
          </div>

          <ShopEmptyState
            v-else-if="
              !isLoading &&
              !categoryProducts.data?.length &&
              !tagProducts.data?.length &&
              !products.length
            "
            :has-filters="activeFilterCount > 0"
            @clear-filters="resetFilters"
          />
        </div>

        <Pagination v-if="!isLoading" />
      </div>
    </div>

    <Teleport v-if="isDesktop" to="#filterSidebarTarget">
      <FilterPanel v-if="!categorySlug && !tagSlug" />
    </Teleport>
    <Teleport v-else-if="filterDrawerRef?.bodyEl" to="#filterDrawerBodyTarget">
      <FilterPanel v-if="!categorySlug && !tagSlug" />
    </Teleport>
  </main>

  <FilterDrawer ref="filterDrawerRef" />
</template>

<style scoped>
/* --------------------------------------------------
   Search
-------------------------------------------------- */
.filter-search {
  margin-bottom: 16px;
}
.filter-search__input {
  position: relative;
  display: flex;
  align-items: center;
}

.filter-search__icon {
  position: absolute;
  right: 14px;
  color: var(--c-text-muted, #94a3b8);
  font-size: 13px;
  pointer-events: none;
}

.filter-search__input input {
  width: 100%;
  height: 44px;
  padding: 0 40px 0 13px;
  border: 1px solid var(--c-border, #e5e7eb);
  border-radius: 10px;
  outline: none;
  background: var(--c-background, #f8fafc);
  color: var(--c-text, #1f2937);
  font-family: inherit;
  font-size: 13px;
  transition: border-color 0.2s ease, box-shadow 0.2s ease,
    background-color 0.2s ease;
}

.filter-search__input input::placeholder {
  color: var(--c-text-muted, #94a3b8);
}

.filter-search__input input:focus {
  border-color: var(--c-gold, #c8a96a);
  background: var(--c-surface, #ffffff);
  box-shadow: 0 0 0 3px rgba(200, 169, 106, 0.1);
}
</style>