<script setup>
import { shopCategoryLink } from '@/utils/url'

defineProps({
  categoryName: { type: String, required: true },
  children: { type: Array, required: true },
})
</script>

<template>
  <nav class="subcat-nav" :aria-label="`زیردسته‌های ${categoryName}`">
    <a v-for="child in children" :key="child.id" :href="shopCategoryLink(child.id)">
      {{ child.name }}
    </a>
  </nav>
</template>
