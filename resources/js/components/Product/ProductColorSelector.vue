<script setup>
import { COLORS } from '@/data/shop'

const props = defineProps({
  productColors: { type: Array, required: true }, // [{ id, available }]
  modelValue: { type: String, default: null },
})
const emit = defineEmits(['update:modelValue'])

function swatch(id) {
  return COLORS.find((c) => c.id === id)
}
function currentName() {
  return swatch(props.modelValue)?.name ?? ''
}
</script>

<template>
  <div class="pdp-option">
    <div class="pdp-option__head">
      <p class="pdp-option__title">رنگ</p>
      <p class="pdp-option__value">{{ currentName() }}</p>
    </div>
    <div class="color-list" role="group" aria-label="انتخاب رنگ">
      <template v-for="pc in productColors" :key="pc.id">
        <button
          v-if="swatch(pc.id)"
          type="button"
          class="color-btn"
          :style="{
            background: swatch(pc.id).hex,
            borderColor: pc.id === 'white' || pc.id === 'cream' ? 'var(--c-border-strong)' : undefined,
          }"
          :aria-disabled="!pc.available ? 'true' : undefined"
          :aria-pressed="pc.available ? (modelValue === pc.id ? 'true' : 'false') : undefined"
          :aria-label="pc.available ? swatch(pc.id).name : `${swatch(pc.id).name} — ناموجود`"
          @click="pc.available && emit('update:modelValue', pc.id)"
        />
      </template>
    </div>
  </div>
</template>
