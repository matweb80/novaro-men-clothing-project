<template>
  <TagProductsPage :tag-slug="tagSlug"/>
</template>

<script setup>
import TagProductsPage from "@/components/pages/TagProductsPage.vue";

const props = defineProps(["tagSlug"]);
</script>