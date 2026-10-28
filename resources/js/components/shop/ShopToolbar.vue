<script setup>
import { toFa } from "@/utils/formatPrice";
import SortMenu from "./SortMenu.vue";
import { inject } from "vue";

defineEmits(["open-filters"]);

const props = defineProps({
  count: { type: Number, required: true },
  totalCount: { type: Number, required: true },
});

const resetFilters = inject("resetFilters");
const sort = inject("sort");
const setSort = inject("setSort");
const activeFilterCount = inject("activeFilterCount");
</script>

<template>
  <div class="shop-toolbar">
    <div class="shop-toolbar__left">
      <span class="shop-toolbar__count"
        >نمایش {{ count > 0 ? toFa(count) : 0 }} محصول ( مجموع : {{ toFa(totalCount) }} محصول )
      </span>
      <template v-if="activeFilterCount > 0">
        <span class="shop-toolbar__count"
          >{{ toFa(activeFilterCount) }} فیلتر فعال</span
        >
        <button
          type="button"
          class="shop-toolbar__clear"
          @click="resetFilters"
        >
          پاک کردن فیلترها
        </button>
      </template>
    </div>

    <div class="shop-toolbar__right">
      <button
        type="button"
        class="filter-trigger"
        aria-haspopup="dialog"
        aria-controls="filterDrawer"
        @click="$emit('open-filters', $event.currentTarget)"
      >
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M4 6h16M7 12h10M10 18h4" />
        </svg>
        <span>فیلترها</span>
        <span v-if="activeFilterCount > 0" class="filter-trigger__count">{{
          toFa(activeFilterCount)
        }}</span>
      </button>
      <SortMenu :model-value="sort" @update:model-value="setSort" />
    </div>
  </div>
</template>
