<script setup>
import { formatToman, discountPercent, toFa } from '@/utils/formatPrice'

defineProps({
  price: { type: Number, required: true },
  originalPrice: { type: Number, default: null },
  isSpecial: { type: Boolean, default: null },
})
</script>

<template>
  <div class="product-info__price">
    <span class="price" :class="{ 'price--sale': originalPrice }">
      <span class="latin" style="direction: rtl">{{ formatToman(price) }}</span>
    </span>
    <template v-if="isSpecial">
      <span class="price price--original">
        <span class="latin" style="direction: rtl">{{ formatToman(originalPrice) }}</span>
      </span>
      <span class="price-discount">{{ toFa(discountPercent(originalPrice, price)) }}٪ تخفیف</span>
    </template>
  </div>
</template>
