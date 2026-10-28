<script setup>
import ProductCard from "./ProductCard.vue";
import BaseSlider from "../UI/BaseSlider.vue";
import { onMounted } from "vue";
import { useSliderProducts } from "../../composables/useSliderProducts";

defineProps({
  products: { type: Array, required: true },
});
const metaBrand = (product) => {
  for (const attr of product.attribute_values) {
    if (attr.attribute_id === 6) {
      return attr.value;
    }
  }
};
</script>

<template>
  <base-slider :products="products" class="base-slider">
    <template #product="{ product }">
      <product-card :product="product"></product-card>
      <meta itemprop="name" :content="product.title" />
      <meta itemprop="image" :content="`${product.image}`" />
      <meta itemprop="sku" :content="product.sku || 'شناسه نامشخص'" />
      <meta itemprop="brand" :content="metaBrand(product) || 'برند نامشخص'" />
      <meta itemprop="offers" itemscope itemtype="https://schema.org/Offer" />
      <meta itemprop="priceCurrency" content="IRR" />
      <meta itemprop="price" :content="product.sale_price ?? product.price" />
      <meta itemprop="availability" content="https://schema.org/InStock" />
      <meta itemprop="url" :content="`/products/${product.slug}`" />
    </template>
  </base-slider>
</template>