<template>
  <div :class="{ 'skeleton-card': isLoading }">
    <slot></slot>
  </div>
</template>

<script>
export default {
  inject: ["isLoading"],
};
</script>

<style scoped>
div {
  max-width: 40rem;
  padding: 1.5rem 1rem;
  border-radius: 12px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.26);
  display: flex;
  flex-direction: column;
  align-items: center;
}

.skeleton-card {
  height: 300px;
  border-radius: 12px;
  background: linear-gradient(90deg, #eee 25%, #ddd 37%, #eee 63%);
  animation: shimmer 1.4s infinite;
}
</style>