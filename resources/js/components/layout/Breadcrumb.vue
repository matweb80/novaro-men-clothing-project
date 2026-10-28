<template>
  <nav class="breadcrumb" aria-label="مسیر دسترسی">
    <a href="/">نوواررو</a>
    <span class="breadcrumb__sep">/</span>
    <span class="breadcrumb__current">{{ currentLabel }}</span>
  </nav>
</template>

<script setup>
defineProps({
  currentLabel: {
    type: String,
    required: true,
    default: "فروشگاه",
  },
});
</script>