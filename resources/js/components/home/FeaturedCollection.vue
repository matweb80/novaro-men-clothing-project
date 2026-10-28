<script setup>
import ImagePlate from "@/components/ui/ImagePlate.vue";
import { ref } from "vue";
import { useMediaQuery } from "../../composables/useMediaQuery";

const isHovered = ref(false);

const isTablet = useMediaQuery("(max-width: 992px)");
</script>

<template>
  <section class="collection-feature" aria-labelledby="collectionTitle">
    <ImagePlate
      class="collection-feature__media"
      src="images/homepage/category-shirts.webp"
      alt="مرد با پیراهن سفید کلاسیک نوواررو"
    />
    <div class="container" v-if="isTablet">
      <div class="collection-feature__caption">
        <p class="eyebrow">کالکشن منتخب</p>
        <h2 id="collectionTitle">کالکشن کلاسیک</h2>
        <p class="collection-feature__text">
          مجموعه‌ای محدود از قطعات کلاسیک نوواررو، طراحی‌شده برای ماندگاری در هر
          فصل.
        </p>
        <a href="#" class="btn btn--outline">مشاهده کالکشن</a>
      </div>
    </div>

    <div class="cube-card" v-else>
      <div id="perspektive">
        <div
          id="cube"
          :class="{ 'is-hovered': isHovered }"
          @mouseenter="isHovered = true"
          @mouseleave="isHovered = false"
        >
          <div class="cube-face cube-face--front">
            <div class="collection-feature__caption">
              <p class="eyebrow">کالکشن منتخب</p>
              <h2 id="collectionTitle">کالکشن کلاسیک</h2>
              <p class="collection-feature__text">
                مجموعه‌ای محدود از قطعات کلاسیک نوواررو، طراحی‌شده برای ماندگاری
                در هر فصل.
              </p>
            </div>
          </div>
          <div class="cube-face cube-face--left">
            <div class="collection-feature__caption">
              <a href="#" class="btn btn--outline">مشاهده کالکشن</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.cube-card {
  width: 400px;
  height: 150px;
}

#perspektive {
  width: 100%;
  height: 100%;
  perspective: 1000px;
  margin-right: 170px;
}
#perspektive #cube {
  width: 100%;
  height: 100%;
  position: relative;
  transform-style: preserve-3d;
  transform: scale(0.9);
  transition: transform 500ms ease-in-out;
  will-change: transform;
}
#perspektive #cube.is-hovered {
  cursor: pointer;
  transform: rotateY(90deg);
}
#perspektive #cube:focus {
  cursor: pointer;
  transform: rotateY(90deg);
}

#perspektive #cube div {
  position: absolute;
  -webkit-filter: blur(0);
  filter: contrast(1.4) blur(0);
  inset: 0;

  backface-visibility: hidden;
}
#perspektive #cube .cube-face--front {
  transform: translateZ(200px);
}
#perspektive #cube .cube-face--left {
  transform: translateX(-200px) rotateY(-90deg);
}
#perspektive #cube .cube-face--left div {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
