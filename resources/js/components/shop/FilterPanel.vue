<template>
  <aside class="products-filter">
    <div class="filter-card">
      <!-- Filter Header -->
      <div class="filter-card__header" v-if="!isTablet">
        <span class="filter-card__title">
          <font-awesome-icon
            icon="fa-solid fa-sliders"
            class="filter-card__title-icon"
          />
          فیلتر محصولات
        </span>
      </div>

      <!-- Filter Content -->

      <div class="filter-card__body">
        <!-- Search -->
        <div class="filter-section filter-search">
          <label for="product-filter-search" class="filter-section__title">
            جستجو
          </label>

          <div class="filter-search__input">
            <font-awesome-icon
              icon="fa-solid fa-magnifying-glass"
              class="filter-search__icon"
            />

            <input
              id="product-filter-search"
              v-model="filters.search"
              type="text"
              name="search"
              autocomplete="off"
              placeholder="جستجو در محصولات..."
            />
          </div>
        </div>

        <!-- Price -->
        <div class="filter-section">
          <div class="filter-section__heading">
            <h3 class="filter-section__title">محدوده قیمت</h3>
          </div>

          <div v-if="isPriceRangeReady" class="price-range">
            <div class="price-range__values">
              <span> {{ formatPrice(minPrice) }} تومان </span>

              <span> {{ formatPrice(maxPrice) }} تومان </span>
            </div>

            <div class="price-range__slider">
              <div class="price-range__track"></div>

              <div
                class="price-range__progress"
                :style="{
                  right: `${minPricePercent}%`,
                  left: `${100 - maxPricePercent}%`,
                }"
              ></div>

              <input
                v-model.number="filters.min_price"
                type="range"
                class="price-range__input price-range__input--min"
                :min="priceRange.min"
                :max="priceRange.max"
                :step="PRICE_STEP"
                @input="handleMinPrice"
              />

              <input
                v-model.number="filters.max_price"
                type="range"
                class="price-range__input price-range__input--max"
                :min="priceRange.min"
                :max="priceRange.max"
                :step="PRICE_STEP"
                @input="handleMaxPrice"
              />
            </div>
          </div>

          <div v-else class="price-range__loading">
            در حال دریافت محدوده قیمت...
          </div>
        </div>

        <!-- Special Price -->
        <div class="filter-section filter-section--compact">
          <label class="filter-switch">
            <input
              v-model="filters.special_price"
              type="checkbox"
              :true-value="true"
              :false-value="false"
            />

            <span class="filter-switch__control">
              <span class="filter-switch__check">
                <font-awesome-icon icon="fa-solid fa-check" />
              </span>
            </span>

            <span class="filter-switch__content">
              <span class="filter-switch__title"> فقط محصولات تخفیف‌دار </span>
              <span class="filter-switch__description">
                نمایش محصولات دارای تخفیف
              </span>
            </span>
          </label>
        </div>

        <!-- Dynamic Attributes -->
        <div v-if="attributes?.length" class="filter-attributes">
          <div
            v-for="attr in attributes"
            :key="attr.id"
            class="filter-attribute"
          >
            <filter-products-attribute-item :attr="attr" :filters="filters" />
          </div>
        </div>

        <!-- Actions -->
        <div class="filter-actions" v-if="!isTablet">
          <base-button
            class="filter-actions__apply"
            @click="applyFiltersWithPrice"
          >
            <font-awesome-icon icon="fa-solid fa-check" />

            <span>
              {{ isLoading ? "در حال جستجو..." : "اعمال فیلترها" }}
            </span>
          </base-button>

          <base-button class="filter-actions__reset" @click="resetFilters">
            <font-awesome-icon icon="fa-solid fa-rotate-right" />

            <span>حذف فیلترها</span>
          </base-button>
        </div>
      </div>
    </div>
  </aside>
</template>

<script>
import { computed, inject, onBeforeMount, ref, watch } from "vue";
import FilterProductsAttributeItem from "../Product/FilterProductsAttributeItem.vue";
import { useMediaQuery } from "../../composables/useMediaQuery";
import BaseButton from "../UI/BaseButton.vue";

export default {
  components: {
    FilterProductsAttributeItem,
    BaseButton,
  },

  inject: [
    "attributes",
    "isLoading",
    "filters",
    "priceRange",
    "applyFilters",
    "resetFilters",
  ],

  setup() {
    const isTablet = useMediaQuery("(max-width: 992px)");
    const isTabletOpen = ref(false);

    const filters = inject("filters");
    const applyFilters = inject("applyFilters");

    const isTabletShowCollapseAccordion = computed(() => {
      return isTablet.value ? "" : "show";
    });

    const isTabletExpandAccordion = computed(() => {
      return isTablet.value ? "false" : "true";
    });

    const toggleTabletFilter = () => {
      if (!isTablet.value) {
        return;
      }

      isTabletOpen.value = !isTabletOpen.value;
    };

    // price range
    const priceRange = inject("priceRange");
    const PRICE_STEP = 100000;

    const minPrice = computed(() => {
      return filters.min_price ?? priceRange.min;
    });

    const maxPrice = computed(() => {
      return filters.max_price ?? priceRange.max;
    });

    const isPriceRangeReady = computed(() => {
      return (
        Number(priceRange.max) > Number(priceRange.min) &&
        Number(priceRange.max) > 0
      );
    });

    /*
     * جلوگیری از عبور min از max
     */
    watch(minPrice, (value) => {
      if (value >= maxPrice.value) {
        minPrice.value = maxPrice.value - priceRange.step;
      }
    });

    watch(maxPrice, (value) => {
      if (value <= minPrice.value) {
        maxPrice.value = minPrice.value + priceRange.step;
      }
    });

    /*
     * درصد قرارگیری thumbها
     */
    const minPricePercent = computed(() => {
      const min = Number(priceRange.min);
      const max = Number(priceRange.max);
      const value = Number(minPrice.value);

      if (max <= min) {
        return 0;
      }

      return Math.min(100, Math.max(0, ((value - min) / (max - min)) * 100));
    });

    const maxPricePercent = computed(() => {
      const min = Number(priceRange.min);
      const max = Number(priceRange.max);
      const value = Number(maxPrice.value);

      if (max <= min) {
        return 100;
      }

      return Math.min(100, Math.max(0, ((value - min) / (max - min)) * 100));
    });

    const formatPrice = (value) => {
      return new Intl.NumberFormat("fa-IR").format(value);
    };

    const handleMinPrice = () => {
      const min = Number(filters.min_price);
      const max = Number(maxPrice.value);

      if (min >= max) {
        filters.min_price = Math.max(priceRange.min, max - PRICE_STEP);
      }
    };

    const handleMaxPrice = () => {
      const min = Number(minPrice.value);
      const max = Number(filters.max_price);

      if (max <= min) {
        filters.max_price = Math.min(priceRange.max, min + PRICE_STEP);
      }
    };

    const applyFiltersWithPrice = () => {
      applyFilters(1);
    };

    onBeforeMount(() => {
      if (filters.min_price || filters.max_price) {
        minPrice.value = filters.min_price;
        maxPrice.value = filters.max_price;
      }
    });

    return {
      isTablet,
      isTabletOpen,
      isTabletShowCollapseAccordion,
      isTabletExpandAccordion,
      toggleTabletFilter,
      priceRange,
      PRICE_STEP,
      minPrice,
      maxPrice,
      minPricePercent,
      maxPricePercent,
      isPriceRangeReady,
      formatPrice,
      handleMinPrice,
      handleMaxPrice,
      applyFiltersWithPrice,
    };
  },
};
</script>

<style scoped>
.products-filter {
  width: 100%;
}

.filter-card {
  width: 100%;
  overflow: hidden;
  background: var(--c-surface, #ffffff);
  border-radius: 16px;
}

/* --------------------------------------------------
   Header
-------------------------------------------------- */

.filter-card__header {
  padding: 12px 12px 0;
}

.filter-card__toggle {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  min-height: 64px;
  padding: 0 20px;
  border: 0;
  background: transparent;
  color: var(--c-text, #1f2937);
  cursor: pointer;
  text-align: right;
}

.filter-card__title {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-size: 15px;
  font-weight: 700;
}

.filter-card__title-icon {
  color: var(--c-gold, #c8a96a);
  font-size: 15px;
}

.filter-card__angle {
  flex-shrink: 0;
  color: var(--c-text-muted, #64748b);
  font-size: 13px;
  transition: transform 0.25s ease;
}

.filter-card__toggle[aria-expanded="true"] .filter-card__angle {
  transform: rotate(180deg);
}

/* --------------------------------------------------
   Body
-------------------------------------------------- */

.filter-card__body {
  padding: 20px;
}

.filter-section {
  padding-bottom: 22px;
  margin-bottom: 22px;
  border-bottom: 1px solid var(--c-border, #e5e7eb);
}

.filter-section--compact {
  padding-bottom: 20px;
}

.filter-section__heading {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 14px;
}

.filter-section__title {
  display: block;
  margin: 0 0 11px;
  color: var(--c-text, #1f2937);
  font-size: 14px;
  font-weight: 700;
}

/* --------------------------------------------------
   Search
-------------------------------------------------- */

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

/* --------------------------------------------------
   Price
-------------------------------------------------- */
.price-range {
  width: 100%;
}

.price-range__loading {
  min-height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--c-text-muted, #94a3b8);
  font-size: 11px;
}

.price-range__values {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 18px;
  color: var(--c-text-muted, #64748b);
  font-size: 11px;
}

.price-range__values span {
  direction: rtl;
  white-space: nowrap;
}

.price-range__slider {
  position: relative;
  width: 100%;
  height: 24px;
  direction: rtl;
}

.price-range__track,
.price-range__progress {
  position: absolute;
  top: 50%;
  height: 4px;
  border-radius: 999px;
  transform: translateY(-50%);
}

.price-range__track {
  right: 0;
  left: 0;
  background: var(--c-border, #e5e7eb);
}

.price-range__progress {
  background: var(--c-gold, #c8a96a);
}

.price-range__input {
  position: absolute;
  top: 50%;
  right: 0;
  width: 100%;
  height: 24px;
  margin: 0;
  padding: 0;
  appearance: none;
  background: transparent;
  pointer-events: none;
  transform: translateY(-50%);
}

.price-range__input::-webkit-slider-runnable-track {
  height: 4px;
  background: transparent;
}

.price-range__input::-moz-range-track {
  height: 4px;
  background: transparent;
}

.price-range__input::-webkit-slider-thumb {
  width: 16px;
  height: 16px;
  margin-top: -6px;
  border: 2px solid var(--c-gold, #c8a96a);
  border-radius: 50%;
  background: #fff;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.12);
  appearance: none;
  cursor: pointer;
  pointer-events: auto;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.price-range__input::-moz-range-thumb {
  width: 12px;
  height: 12px;
  border: 2px solid var(--c-gold, #c8a96a);
  border-radius: 50%;
  background: #fff;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.12);
  cursor: pointer;
  pointer-events: auto;
}

.price-range__input::-webkit-slider-thumb:hover {
  transform: scale(1.15);
  box-shadow: 0 0 0 5px rgba(200, 169, 106, 0.12);
}

/* Remove number arrows */

.price-field__input input::-webkit-outer-spin-button,
.price-field__input input::-webkit-inner-spin-button {
  margin: 0;
  appearance: none;
}

.price-field__input input[type="number"] {
  appearance: textfield;
}

/* --------------------------------------------------
   Switch / checkbox
-------------------------------------------------- */

.filter-switch {
  display: flex;
  align-items: center;
  gap: 11px;
  width: 100%;
  margin: 0;
  cursor: pointer;
  user-select: none;
}

.filter-switch > input {
  position: absolute;
  width: 1px;
  height: 1px;
  opacity: 0;
  pointer-events: none;
}

.filter-switch__control {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 19px;
  height: 19px;
  flex-shrink: 0;
  border: 1px solid var(--c-border-strong, #cbd5e1);
  border-radius: 5px;
  background: var(--c-surface, #ffffff);
  transition: border-color 0.2s ease, background-color 0.2s ease;
}

.filter-switch__check {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ffffff;
  font-size: 9px;
  opacity: 0;
  transform: scale(0.7);
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.filter-switch > input:checked + .filter-switch__control {
  border-color: var(--c-gold, #c8a96a);
  background: var(--c-gold, #c8a96a);
}

.filter-switch > input:checked + .filter-switch__control .filter-switch__check {
  opacity: 1;
  transform: scale(1);
}

.filter-switch__content {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.filter-switch__title {
  color: var(--c-text, #1f2937);
  font-size: 13px;
  font-weight: 600;
}

.filter-switch__description {
  color: var(--c-text-muted, #94a3b8);
  font-size: 10px;
}

/* --------------------------------------------------
   Dynamic attributes
-------------------------------------------------- */

.filter-attributes {
  display: flex;
  flex-direction: column;
}

.filter-attribute {
  border-bottom: 1px solid var(--c-border, #e5e7eb);
}

.filter-attribute:last-child {
  border-bottom: 0;
}

/* --------------------------------------------------
   Actions
-------------------------------------------------- */

.filter-actions {
  display: flex;
  flex-direction: column;
  gap: 9px;
  padding-top: 20px;
}

.filter-actions__apply,
.filter-actions__reset {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  min-height: 44px;
  border-radius: 9px;
  font-family: inherit;
  font-size: 13px;
  font-weight: 600;
  transition: transform 0.2s ease, opacity 0.2s ease;
}

.filter-actions__apply {
  background: var(--c-gold, #c8a96a);
}

.filter-actions__reset {
  background: transparent;
  border: 1px solid var(--c-border, #e5e7eb);
  color: var(--c-text-muted, #64748b);
}

.filter-actions__apply:hover,
.filter-actions__reset:hover {
  transform: translateY(-1px);
}

.filter-actions__apply:active,
.filter-actions__reset:active {
  transform: translateY(0);
}

/* --------------------------------------------------
   Responsive
-------------------------------------------------- */

@media (max-width: 991.98px) {
  .filter-card__toggle {
    min-height: 58px;
  }

  .filter-card__body {
    padding: 18px;
  }
}

@media (max-width: 575.98px) {
  .filter-card {
    border-radius: 12px;
  }

  .filter-card__toggle {
    padding: 0 16px;
  }

  .filter-card__body {
    padding: 16px;
  }

  .price-range__fields {
    grid-template-columns: 1fr;
    gap: 10px;
  }

  .price-range__separator {
    display: none;
  }
}
</style>