<script setup>
import { ref } from 'vue'
import { SIZES } from '@/data/shop'

const props = defineProps({
  availableSizes: { type: Array, required: true },
  modelValue: { type: String, default: null },
})
const emit = defineEmits(['update:modelValue'])

const rootEl = ref(null)
function focusFirstAvailable() {
  rootEl.value?.querySelector(".size-btn:not([aria-disabled='true'])")?.focus()
}
defineExpose({ focusFirstAvailable })
</script>

<template>
  <div ref="rootEl" class="pdp-option">
    <div class="pdp-option__head">
      <p class="pdp-option__title">سایز</p>
      <p class="pdp-option__value">راهنمای سایز</p>
    </div>
    <div class="size-list" role="group" aria-label="انتخاب سایز">
      <button
        v-for="size in SIZES"
        :key="size"
        type="button"
        class="size-btn latin"
        :aria-disabled="!availableSizes.includes(size) ? 'true' : undefined"
        :aria-pressed="availableSizes.includes(size) ? (modelValue === size ? 'true' : 'false') : undefined"
        :aria-label="availableSizes.includes(size) ? `سایز ${size}` : `${size} — ناموجود`"
        @click="availableSizes.includes(size) && emit('update:modelValue', size)"
      >
        {{ size }}
      </button>
    </div>
  </div>
</template>
