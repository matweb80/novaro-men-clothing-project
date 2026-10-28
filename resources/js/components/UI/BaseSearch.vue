<template>
  <teleport to="body" :disabled="!overlay">
    <div
      v-if="isOpen"
      :class="['nv-search', { 'nv-search--overlay': overlay }]"
      @keydown.esc="close"
    >
      <button
        v-if="overlay"
        class="nv-search__backdrop"
        aria-label="بستن جست‌وجو"
        @click="close"
      />
      <section
        class="nv-search__panel"
        role="dialog"
        :aria-modal="overlay ? 'true' : undefined"
        aria-label="جست‌وجوی محصولات"
      >
        <div class="nv-search__field">
          <!-- <font-awesome-icon icon="fa-solid fa-search" aria-hidden="true" /> -->
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="11" cy="11" r="7" />
            <line x1="21" y1="21" x2="16.5" y2="16.5" />
          </svg>
          <input
            ref="input"
            v-model="query"
            type="search"
            name="search"
            placeholder="نام محصول، دسته‌بندی یا کالکشن"
            autocomplete="off"
            @input="search"
            @focus="isVisibleDropdown = true"
          />
          <button
            v-if="query"
            class="nv-search__clear"
            type="button"
            aria-label="پاک کردن جست‌وجو"
            @click="clear"
          >
            <font-awesome-icon icon="fa-solid fa-xmark" />
          </button>
          <button
            v-if="overlay"
            class="nv-search__close"
            type="button"
            aria-label="بستن جست‌وجو"
            @click="close"
          >
            <font-awesome-icon icon="fa-solid fa-xmark" />
          </button>
        </div>

        <div
          v-if="isVisibleDropdown && query"
          class="nv-search__results"
          aria-live="polite"
        >
          <div v-if="isLoading" class="nv-search__state">در حال جست‌وجو…</div>
          <template v-else>
            <div v-if="products.length" class="nv-search__group">
              <h3>محصولات</h3>
              <a
                v-for="product in products"
                :key="`p-${product.id}`"
                :href="`/products/${product.slug}`"
                >{{ product.title }}</a
              >
            </div>
            <div v-if="categories.length" class="nv-search__group">
              <h3>دسته‌بندی‌ها</h3>
              <a
                v-for="category in categories"
                :key="`c-${category.id}`"
                :href="`/categories/${category.slug}`"
                >{{ category.name }}</a
              >
            </div>
            <div v-if="tags.length" class="nv-search__group">
              <h3>کالکشن‌ها</h3>
              <a
                v-for="tag in tags"
                :key="`t-${tag.id}`"
                :href="`/tags/${tag.slug}`"
                >{{ tag.name }}</a
              >
            </div>
            <p v-if="error && !hasResults" class="nv-search__state">
              موردی پیدا نشد.
            </p>
            <a
              v-if="hasResults"
              class="nv-search__all"
              :href="`/products?search=${encodeURIComponent(query)}`"
              >مشاهدهٔ همهٔ نتایج</a
            >
          </template>
        </div>
        <p v-else-if="overlay" class="nv-search__hint">
          برای یافتن محصول یا کالکشن موردنظر جست‌وجو کنید.
        </p>
      </section>
    </div>
  </teleport>
</template>

<script>
import { nextTick, watch } from "vue";
import { useSearch } from "../../hooks/useSearch.js";

export default {
  props: {
    show: { type: Boolean, default: true },
    overlay: { type: Boolean, default: false },
  },
  emits: ["close"],
  setup(props, { emit }) {
    const state = useSearch();
    const close = () => {
      state.isVisibleDropdown.value = false;
      emit("close");
    };
    const clear = () => {
      state.query.value = "";
      state.products.value = [];
      state.categories.value = [];
      state.tags.value = [];
      state.error.value = null;
    };
    watch(
      () => props.show,
      async (visible) => {
        if (visible && props.overlay) {
          await nextTick();
        }
      }
    );
    return { ...state, close, clear };
  },
  computed: {
    isOpen() {
      return this.overlay ? this.show : true;
    },
    hasResults() {
      return this.products.length || this.categories.length || this.tags.length;
    },
  },
  mounted() {
    if (this.overlay && this.show)
      this.$nextTick(() => this.$refs.input?.focus());
  },
  updated() {
    if (this.overlay && this.show && document.activeElement === document.body)
      this.$refs.input?.focus();
  },
};
</script>

<style scoped>
.nv-search--overlay {
  position: fixed;
  inset: 0;
  z-index: var(--nv-z-search);
  display: grid;
  place-items: start center;
  padding: clamp(1rem, 8vh, 5rem) var(--nv-gutter);
  font-family: var(--nv-font-sans);
}
.nv-search__backdrop {
  position: absolute;
  inset: 0;
  border: 0;
  background: rgb(15 23 42 / 0.55);
  cursor: default;
}
.nv-search__panel {
  position: relative;
  inline-size: min(100%, 46rem);
  background: var(--nv-surface);
  border: 1px solid var(--nv-border);
  box-shadow: var(--nv-shadow-md);
}
.nv-search__field {
  display: flex;
  align-items: center;
  gap: var(--nv-space-3);
  min-block-size: 4rem;
  padding-inline: var(--nv-space-4);
  border-block-end: 1px solid var(--nv-border);
  color: var(--nv-text-muted);
}
.nv-search__field input {
  flex: 1;
  min-inline-size: 0;
  border: 0;
  outline: 0;
  background: transparent;
  color: var(--nv-text);
}
.nv-search__clear,
.nv-search__close {
  border: 0;
  background: transparent;
  color: var(--nv-text);
  cursor: pointer;
  padding: var(--nv-space-2);
}
.nv-search__results {
  max-block-size: min(58vh, 31rem);
  overflow: auto;
  padding: var(--nv-space-5);
}
.nv-search__group + .nv-search__group {
  margin-block-start: var(--nv-space-5);
}
.nv-search__group h3 {
  margin-block-end: var(--nv-space-2);
  font-size: var(--nv-text-small);
}
.nv-search__group a,
.nv-search__all {
  display: block;
  padding-block: var(--nv-space-2);
  color: var(--nv-text);
}
.nv-search__group a:hover,
.nv-search__all:hover {
  color: var(--nv-gold-hover);
}
.nv-search__all {
  margin-block-start: var(--nv-space-4);
  border-block-start: 1px solid var(--nv-border);
  font-weight: 700;
}
.nv-search__state,
.nv-search__hint {
  padding: var(--nv-space-5);
  color: var(--nv-text-muted);
  text-align: center;
}
</style>
