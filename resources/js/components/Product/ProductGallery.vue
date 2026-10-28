<script setup>
import { ref, onMounted } from "vue";
import { useProductGallery } from "@/composables/useProductGallery";
import ProductGalleryStage from "./ProductGalleryStage.vue";
import ProductGalleryThumbs from "./ProductGalleryThumbs.vue";
import ProductGalleryDots from "./ProductGalleryDots.vue";
import ProductLightbox from "./ProductLightbox.vue";

const props = defineProps({
  images: { type: Array, required: true },
});

const gallery = useProductGallery(props.images.length);
const stageComponentRef = ref(null);

onMounted(() => {
  gallery.stageEl.value = stageComponentRef.value?.stageEl?.value ?? null;
});
</script>

<template>
  <section class="gallery" aria-label="گالری تصاویر محصول">
    <ProductGalleryThumbs
      :count="images.length"
      :active-image="gallery.activeImage.value"
      @select="gallery.goToImage"
    />

    <ProductGalleryStage
      ref="stageComponentRef"
      :images="images"
      :active-image="gallery.activeImage.value"
      @open-lightbox="gallery.openLightbox"
      @next="gallery.next"
      @prev="gallery.prev"
      @stage-keydown="gallery.onStageKeydown"
    />

    <div>
      <ProductGalleryDots
        :count="images.length"
        :active-image="gallery.activeImage.value"
        @select="gallery.goToImage"
      />
    </div>
  </section>

  <ProductLightbox
    :images="images"
    :active-image="gallery.activeImage.value"
    :is-open="gallery.lightboxOpen.value"
    :trigger-el="gallery.lightboxTriggerEl.value"
    @close="gallery.closeLightbox"
    @step="gallery.lightboxStep"
  />
</template>
