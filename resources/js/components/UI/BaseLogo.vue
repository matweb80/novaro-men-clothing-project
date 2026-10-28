<template>
  <a href="/" class="brand" aria-label="نوواررو، بازگشت به صفحه اصلی">
    <img
      class="brand__mark"
      :src="BRAND_MARK"
      alt="NOVARO — Defined by Style"
    />
    <span class="brand__word latin">NOVARO</span>
  </a>
</template>

<script>
import { BRAND_MARK } from "@/assets/images/brand-mark";


export default {
  data() {
    return { BRAND_MARK };
  },
};
</script>

<style scoped>
.nv-logo {
  display: block;
  inline-size: clamp(7.5rem, 12vw, 10.5rem);
  block-size: auto;
  object-fit: contain;
}
</style>
