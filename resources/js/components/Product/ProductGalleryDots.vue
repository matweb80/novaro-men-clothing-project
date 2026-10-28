<script setup>
import { toFa } from '@/utils/formatPrice'

defineProps({
  count: { type: Number, required: true },
  activeImage: { type: Number, required: true },
})
const emit = defineEmits(['select'])
</script>

<template>
  <div class="gallery__dots">
    <button
      v-for="i in count"
      :key="i"
      type="button"
      class="gallery__dot"
      :aria-current="i - 1 === activeImage ? 'true' : 'false'"
      :aria-label="`تصویر ${toFa(i)}`"
      @click="emit('select', i - 1)"
    />
  </div>
</template>
