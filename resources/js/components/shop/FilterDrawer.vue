<script>
export default {
  inject: ["applyFilters", "resetFilters"],
};
</script>

<script setup>
import { ref, nextTick, inject } from "vue";
import { useBodyScrollLock } from "@/composables/useBodyScrollLock";
import { useFocusTrap } from "@/composables/useFocusTrap";

const isOpen = ref(false);
const drawerEl = ref(null);
const bodyEl = ref(null);
const triggerEl = ref(null);

const { lock, unlock } = useBodyScrollLock();
const { trap, releaseTrap } = useFocusTrap();

function open(triggerButton) {
  triggerEl.value = triggerButton || null;
  isOpen.value = true;
  lock();
  nextTick(() => trap(drawerEl.value));
}
function close() {
  isOpen.value = false;
  unlock();
  releaseTrap();
  triggerEl.value?.focus();
}
function onKeydown(e) {
  if (e.key === "Escape") close();
}

defineExpose({ open, close, bodyEl, isOpen });

const applyFilters = inject("applyFilters");
inject("resetFilters");

const showResults = () => {
  // isOpen.value = false;
  close();
  applyFilters(1);
};
</script>

<template>
  <div
    class="filter-backdrop"
    :class="{ 'is-visible': isOpen }"
    @click="close"
  />
  <div
    ref="drawerEl"
    class="filter-drawer"
    :class="{ 'is-open': isOpen }"
    role="dialog"
    aria-modal="true"
    aria-label="فیلتر محصولات"
    @keydown="onKeydown"
  >
    <div class="filter-drawer__header">
      <p class="filter-drawer__title">
        <font-awesome-icon
          icon="fa-solid fa-sliders"
          class="filter-card__title-icon"
        />فیلترها
      </p>
      <button
        type="button"
        class="icon-btn"
        aria-label="بستن فیلترها"
        @click="close"
      >
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <line x1="6" y1="6" x2="18" y2="18" />
          <line x1="18" y1="6" x2="6" y2="18" />
        </svg>
      </button>
    </div>
    <div id="filterDrawerBodyTarget" ref="bodyEl" class="filter-drawer__body" />
    <div class="filter-drawer__footer">
      <button
        type="button"
        class="btn btn--outline btn--sm"
        @click="resetFilters"
      >
        پاک کردن
      </button>
      <button
        type="button"
        class="btn btn--primary btn--sm"
        @click="showResults"
      >
        نمایش نتایج
      </button>
    </div>
  </div>
</template>

<style scoped>
.filter-card__title-icon {
  color: var(--c-gold, #c8a96a);
  font-size: 15px;
  margin-left: 6px;
}
</style>