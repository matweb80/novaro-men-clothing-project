<script setup>
import ProductCard from "./ProductCard.vue";

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
  <div class="product-grid" role="list">
    <product-card
      v-for="product in products"
      :key="product.id"
      :product="product"
      ><!-- seo -->
      <meta itemprop="name" :content="product.title" />
      <meta itemprop="image" :content="`${product.image}`" />
      <meta itemprop="sku" :content="product.id" />
      <meta itemprop="brand" :content="metaBrand(product) || 'برند نامشخص'" />
      <meta itemprop="offers" itemscope itemtype="https://schema.org/Offer" />
      <meta itemprop="priceCurrency" content="IRR" />
      <meta itemprop="price" :content="product.sale_price ?? product.price" />
      <meta itemprop="availability" content="https://schema.org/InStock" />
      <meta itemprop="url" :content="`/products/${product.slug}`"
    /></product-card>
  </div>
</template>
