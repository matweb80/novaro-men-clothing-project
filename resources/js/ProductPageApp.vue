<template>
  <ProductPage :slug="slug"></ProductPage>
</template>

<script setup>
import ProductPage from "@/components/pages/ProductPage.vue";

const props = defineProps({
  slug: String, // از Blade گرفته میشه: data-slug
});
</script>