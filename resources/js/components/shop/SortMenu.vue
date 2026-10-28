<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { SORT_OPTIONS } from "@/data/shop";
import { useFilteredProducts } from "@/composables/useFilteredProducts";

const props = defineProps({
  modelValue: { type: String, required: true },
});
const emit = defineEmits(["update:modelValue"]);

const isOpen = ref(false);
const wrapEl = ref(null);
const btnEl = ref(null);

const current = () => SORT_OPTIONS.find((o) => o.id === props.modelValue);

function open() {
  isOpen.value = true;
}
function close() {
  isOpen.value = false;
}
function toggle() {
  isOpen.value ? close() : open();
}
function choose(id) {
  emit("update:modelValue", id);
  close();
}
function onOutsideClick(e) {
  if (wrapEl.value && !wrapEl.value.contains(e.target)) close();
}
function onKeydown(e) {
  if (e.key === "Escape") {
    close();
    btnEl.value?.focus();
  }
}

const { loadFiltersFromUrl, applyFilters } = useFilteredProducts();

onMounted(() => document.addEventListener("click", onOutsideClick));
onUnmounted(() => document.removeEventListener("click", onOutsideClick));

window.addEventListener("popstate", () => {
  loadFiltersFromUrl();
  applyFilters(currentPage.value, false);
});
</script>

<template>
  <div
    ref="wrapEl"
    class="sort-menu"
    :class="{ 'is-open': isOpen }"
    @keydown="onKeydown"
  >
    <button
      ref="btnEl"
      type="button"
      class="sort-menu__trigger"
      aria-haspopup="true"
      :aria-expanded="isOpen ? 'true' : 'false'"
      aria-controls="sortPanel"
      @click.stop="toggle"
    >
      <span
        >مرتب‌سازی: <strong>{{ current()?.label }}</strong></span
      >
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path d="M6 9l6 6 6-6" />
      </svg>
    </button>

    <div id="sortPanel" class="sort-menu__panel" role="menu">
      <button
        v-for="opt in SORT_OPTIONS"
        :key="opt.id"
        type="button"
        class="sort-menu__option"
        role="menuitemradio"
        :aria-checked="opt.id === modelValue ? 'true' : 'false'"
        @click="choose(opt.id)"
      >
        {{ opt.label }}
      </button>
    </div>
  </div>
</template>
