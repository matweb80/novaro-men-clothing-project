<script setup>
import { computed, ref, toRef } from "vue";
import { shopCategoryLink } from "@/utils/url";
import { useProductOptions } from "@/composables/useProductOptions";
import { useProductPurchase } from "@/composables/useProductPurchase";
import ProductPrice from "./ProductPrice.vue";
import ProductStock from "./ProductStock.vue";
import ProductPurchaseActions from "./ProductPurchaseActions.vue";

const props = defineProps({
  product: { type: Object, required: true },
  stockInfo: { type: Object, required: true },
  finalPrice: { type: Number, required: false },
});

const productRef = toRef(props, "product");

const items = Object.keys(props.product.categories).map(
  (key) => props.product.categories[key]
);
const category = items.findLast((item) => item);

</script>

<template>
  <section class="product-info" aria-label="اطلاعات محصول">
    <p class="product-info__eyebrow">
      <a v-if="category" :href="`/categories/${category.slug}`">{{
        category.name
      }}</a>
    </p>

    <h1>{{ product.title }}</h1>

    <ProductPrice
      :price="product.price"
      :original-price="finalPrice"
      :is-special="product.is_special"
    />

    <p
      v-if="product.short_desc"
      class="product-info__desc"
      v-html="product.short_desc"
    ></p>

    <ProductStock :tone="stockInfo.tone" :label="stockInfo.label" />

    <div>
      <ProductPurchaseActions :product="product" />
    </div>

    <div class="pdp-reassure">
      <p class="pdp-reassure__row">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M3 7h11v9H3zM14 10h4l3 3v3h-7z" />
          <circle cx="7" cy="18" r="1.6" />
          <circle cx="17.5" cy="18" r="1.6" />
        </svg>
        <span>ارسال بین ۳ تا ۵ روز کاری</span>
      </p>
      <p class="pdp-reassure__row">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M21 12a9 9 0 1 1-3-6.7" />
          <path d="M21 4v5h-5" />
        </svg>
        <span>۷ روز مهلت بازگشت کالا</span>
      </p>
      <p class="pdp-reassure__row">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <rect x="5" y="11" width="14" height="9" rx="1.5" />
          <path d="M8 11V8a4 4 0 0 1 8 0v3" />
        </svg>
        <span>پرداخت امن</span>
      </p>
    </div>

    <p v-if="product.sku" class="pdp-sku">
      کد محصول:
      <span class="latin" style="direction: ltr">{{ product.sku }}</span>
    </p>
  </section>
</template>
