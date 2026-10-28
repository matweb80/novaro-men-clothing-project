<template>
  <Splide v-if="products?.length" ref="splideRef" :options="computedOptions">
    <SplideSlide v-for="(product, i) in products" :key="i">
      <div class="slider-card">
        <slot name="product" :product="product"></slot>
      </div>
    </SplideSlide>
  </Splide>
</template>

<script>
import { computed, onMounted, onUnmounted, ref } from "vue";

export default {
  props: ["products"],
  setup(props) {
    const splideRef = ref(null);
    const screenWidth = ref(window.innerWidth);

    function getPerPage() {
      const width = screenWidth.value;

      if (width <= 576) return 1;
      if (width <= 768) return 2;
      if (width <= 992) return 3;
      return 4;
    }

    const computedOptions = computed(() => {
      const perPage = getPerPage();
      const total = props.products?.length || 0;

      const shouldEnable = total > perPage;

      return {
        type: "slide",
        label: "New Arrival Products Carousel",
        direction: "rtl",
        perPage: perPage,
        perMove: 1,
        arrows: shouldEnable,
        drag: shouldEnable,
        pagination: false,
        lazyLoad: "nearby",
        gap: "24px",
        padding: 8,
        keyboard: true,
        autoHeight: false
      };
    });

    function handleResize() {
      screenWidth.value = window.innerWidth;
    }

    onMounted(() => {
      window.addEventListener("resize", handleResize);
    });

    onUnmounted(() => {
      window.removeEventListener("resize", handleResize);
    });

    return {
      splideRef,
      computedOptions,
    };
  },
};
</script>

<style>
.base-slider .splide__arrow {
  background: transparent;
}

.base-slider .splide__arrow svg {
  width: 20px;
  height: 20px;
  fill: #0f172a;
  transition: all var(--dur-base) ease-in-out;
}

.base-slider .splide__arrow:not(:disabled):hover svg {
  fill: #c8a96a;
}

.base-slider .splide__arrows--rtl .splide__arrow--prev {
  right: -1em !important;
  transform: translateY(-100%);
}
.base-slider .splide__arrows--rtl .splide__arrow--next {
  left: -1em !important;
  transform: translateY(-100%);
}

.base-slider .splide__arrow:disabled {
  cursor: default;
}
.base-slider .splide__arrow:disabled:hover {
  fill: inherit;
}

.slider-card {
  height: 100%;
}

.splide__list {
    padding: 8px 0!important;
}
</style>