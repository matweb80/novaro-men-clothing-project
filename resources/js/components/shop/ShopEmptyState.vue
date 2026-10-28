<script setup>
import { shopCategoryLink } from '@/utils/url'

const props = defineProps({
  hasFilters: { type: Boolean, required: true },
})
const emit = defineEmits(['clear-filters'])
</script>

<template>
  <div class="shop-empty">
    <p class="shop-empty__title">
      {{ hasFilters ? 'محصولی با این فیلترها پیدا نشد' : 'محصولی در این دسته موجود نیست' }}
    </p>
    <p class="shop-empty__text">
      {{
        hasFilters
          ? 'فیلترهای اعمال‌شده را بازبینی کنید یا آن‌ها را پاک کنید.'
          : 'این دسته‌بندی به‌زودی با محصولات جدید به‌روزرسانی می‌شود.'
      }}
    </p>
    <div class="shop-empty__actions">
      <button v-if="hasFilters" type="button" class="btn btn--primary btn--sm" @click="emit('clear-filters')">
        پاک کردن فیلترها
      </button>
      <a :href="shopCategoryLink('clothing').path" class="btn btn--outline btn--sm">
        بازگشت به فروشگاه
      </a>
    </div>
  </div>
</template>
