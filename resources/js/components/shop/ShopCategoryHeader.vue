<script setup>
import { CATEGORY_DESCRIPTIONS } from "@/data/shop";
import { toFa } from "@/utils/formatPrice";
import SubcategoryNav from "@/components/category/SubcategoryNav.vue";

const props = defineProps({
  categoryNode: { type: Object, required: true },
  productCount: { type: Number, required: true },
});
</script>

<template>
  <header class="category-header container">
    <div class="category-header__row">
      <h1>{{ categoryNode.name }}</h1>
      <span class="category-header__count"
        >نمایش {{ productCount > 0 ? toFa(productCount) : 0 }} محصول</span
      >
    </div>

    <p
      v-if="CATEGORY_DESCRIPTIONS[categoryNode.slug]"
      class="category-header__desc"
    >
      {{ CATEGORY_DESCRIPTIONS[categoryNode.slug] }}
    </p>

    <SubcategoryNav
      v-if="categoryNode.child && categoryNode.child.length"
      :category-name="categoryNode.name"
      :children="categoryNode.child"
    />
  </header>
</template>
