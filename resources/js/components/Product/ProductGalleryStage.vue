<script setup>
import { ref } from "vue";
import { toFa } from "@/utils/formatPrice";
import ImagePlate from "@/components/ui/ImagePlate.vue";

const props = defineProps({
  images: { type: Array, required: true },
  activeImage: { type: Number, required: true },
});
const emit = defineEmits(["open-lightbox", "next", "prev", "stage-keydown"]);

const stageEl = ref(null);
defineExpose({ stageEl });
</script>

<template>
  <div class="pdp-gallery" style="position: relative; flex: 1">
    <div
      ref="stageEl"
      class="gallery__stage pdp-gallery__stage"
      tabindex="0"
      :aria-label="`تصویر محصول، ${toFa(activeImage + 1)} از ${toFa(
        images.length
      )}`"
      @keydown="emit('stage-keydown', $event)"
    >
      <button
        v-for="(img, i) in images"
        :key="i"
        type="button"
        class="gallery__slide"
        :aria-label="`بزرگ‌نمایی تصویر ${toFa(i + 1)} — ${img.alt}`"
        @click="emit('open-lightbox', i, $event.currentTarget)"
      >
        <!-- <img :src="img.image ?? ''" :alt="img.alt ?? ''" loading="lazy" /> -->
        <ImagePlate
          class="cat-tile__media"
          :src="img.image ?? ''"
          :alt="img.alt ?? ''"
        />
      </button>
    </div>

    <button
      type="button"
      class="gallery__arrow gallery__arrow--prev"
      aria-label="تصویر قبلی"
      @click="emit('prev')"
    >
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path d="M9 5l7 7-7 7" />
      </svg>
    </button>
    <button
      type="button"
      class="gallery__arrow gallery__arrow--next"
      aria-label="تصویر بعدی"
      @click="emit('next')"
    >
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path d="M15 5l-7 7 7 7" />
      </svg>
    </button>
  </div>
</template>

<style>
.pdp-gallery__stage { position: relative; aspect-ratio: 4/5; background: #EDEFF3; border-radius: var(--r-md); overflow: hidden; }
.pdp-gallery__stage img { width: 100%; height: 100%; object-fit: cover; }
.pdp-gallery__countdown { position: absolute; top: var(--sp-3); inset-inline-start: var(--sp-3); }
.pdp-gallery__thumbs { display: flex; gap: var(--sp-2); margin-top: var(--sp-3); }
.pdp-gallery__thumb { width: 64px; height: 80px; border-radius: var(--r-sm); overflow: hidden; border: 1px solid var(--c-border); padding: 0; cursor: pointer; }
.pdp-gallery__thumb.is-active { border-color: var(--c-navy); }
.pdp-gallery__thumb img { width: 100%; height: 100%; object-fit: cover; }
</style>