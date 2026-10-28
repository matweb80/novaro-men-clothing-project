<template>
  <nav class="breadcrumb" aria-label="breadcrumb">
    <a href="/" class="breadcrumb-item">خانه</a>

    <template v-for="(item, index) in items" :key="index">
      <span class="breadcrumb__sep">/</span>
      <a v-if="index !== items.length - 1" :href="item.url">
        {{ item.label }}
      </a>
      <span v-else class="breadcrumb__current">{{ item.label }}</span>
    </template>
  </nav>
</template>

<script setup>
defineProps({
  items: {
    type: Array,
    default: () => [],
  },
});
</script>

<style scoped>
ul {
  display: flex;
  list-style: none;
}

a.breadcrumb-item {
  text-decoration: none;
}

.breadcrumb-item {
  line-height: 1.7;
}
</style>