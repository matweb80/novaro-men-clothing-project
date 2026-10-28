<script setup>
defineProps({
  tone: { type: String, required: true },
  label: { type: String, required: true },
});
</script>

<template>
  <div class="pdp-stock" :class="`pdp-stock--${tone}`">
    <span class="pdp-stock__dot" aria-hidden="true" />
    <span>{{ label }}</span>
  </div>
</template>
