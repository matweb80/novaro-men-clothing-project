<script setup>
import { toFa } from '@/utils/formatPrice'

defineProps({
  count: { type: Number, required: true },
  activeImage: { type: Number, required: true },
})
const emit = defineEmits(['select'])
</script>

<template>
  <div class="gallery__thumbs" role="tablist" aria-label="تصاویر محصول">
    <button
      v-for="i in count"
      :key="i"
      type="button"
      class="gallery__thumb"
      role="tab"
      :aria-selected="i - 1 === activeImage ? 'true' : 'false'"
      :aria-current="i - 1 === activeImage ? 'true' : 'false'"
      :aria-label="`نمایش تصویر ${toFa(i)}`"
      @click="emit('select', i - 1)"
    >
      <span class="ph__mark latin" aria-hidden="true">{{ toFa(i) }}</span>
    </button>
  </div>
</template>
