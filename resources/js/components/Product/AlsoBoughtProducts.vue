<script setup>
import ProductSlider from "./ProductSlider.vue";

defineProps({
  products: { type: Array, required: true },
});
</script>

<template>
  <section
    v-if="products.length"
    class="pg-section pg-section--tight"
    aria-labelledby="relatedTitle"
  >
    <div class="container">
      <div class="section-head">
        <p class="eyebrow">بقیه خریداری کرده اند</p>
        <h2 id="relatedTitle">مواردی که حتما میپسندید</h2>
      </div>
      <ProductSlider :products="products" />
    </div>
  </section>
</template>
