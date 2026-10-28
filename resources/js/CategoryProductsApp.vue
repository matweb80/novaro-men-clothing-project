<template>
  <CategoryProductsPage :category-slug="categorySlug"/>
</template>

<script setup>
import CategoryProductsPage from "@/components/pages/CategoryProductsPage.vue";

const props = defineProps(["categorySlug"]);
</script>