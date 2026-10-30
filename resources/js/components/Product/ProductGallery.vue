<template>
  <section class="gallery" aria-label="گالری تصاویر محصول">
    <!-- تصاویر -->
    <div class="pdp-gallery product-images" style="position: relative; flex: 1">
      <!-- Main Badge -->
      <div v-if="product.badge" class="product-card__badges">
        <span
          class="tag"
          :data-badge="product.badge.slug"
          :style="{
            '--badge-color': product.badge.color,
          }"
        >
          {{ product.badge.name }}
        </span>
      </div>
      <!-- Sale Countdown -->
      <countdown :endAt="product.sale_end_at" v-if="product.is_special" />

      <div class="gallery__stage pdp-gallery__stage">
        <!-- Main -->
        <Splide
          :options="{
            type: 'loop',
            fixedHeight: 500,
            pagination: false,
            arrows: arrowsEnter,
            direction: 'rtl',
            drag: true,
            lazyLoad: 'nearby',
            breakpoints: {
              920: {
                pagination: true,
                classes: {
                  pagination: 'splide__pagination gallery__dots',
                  page: 'splide__pagination__page gallery__dot',
                },
              },
            },
          }"
          @splide:mounted="onMainMounted"
          class="main-slider"
        >
          <SplideSlide v-for="(img, i) in images" :key="i">
            <ImagePlate
              :src="img.image"
              :alt="`تصویر ${img.alt ?? product.title}`"
              class="main-image"
              :data-splide-lazy="img.image"
              @click="openLightbox(i)"
            />
          </SplideSlide>
        </Splide>
        <!-- Thumbs -->
        <div class="gallery__thumbs" role="tablist" aria-label="تصاویر محصول">
          <Splide
            :options="{
              fixedWidth: 90,
              fixedHeight: 90,
              gap: '0.5rem',
              rewind: true,
              isNavigation: true,
              pagination: false,
              arrows: arrowsEnter,
              direction: 'rtl',
              drag: true,
              lazyLoad: 'nearby',
            }"
            @splide:mounted="onThumbMounted"
            class="thumbs"
          >
            <SplideSlide v-for="(img, i) in images" :key="i">
              <img
                :src="img.image"
                :alt="`تصویر ${img.alt ?? product.title}`"
                class="thumb-image"
                :data-splide-lazy="img.image"
              />
            </SplideSlide>
          </Splide>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, onMounted, ref, watch } from "vue";
import Countdown from "../Countdown/Countdown.vue";

import PhotoSwipeLighbox from "photoswipe/lightbox";
import ImagePlate from "@/components/UI/ImagePlate.vue";

const props = defineProps(["product"]);

const selectedImage = ref(null);

const mainInstance = ref(null);
const thumbInstance = ref(null);

const images = computed(() => {
  return [{ image: props.product.image }, ...props.product.gallery];
});

const lightboxImages = computed(() => {
  return images.value.map((img) => ({
    src: img.image,
    width: 1200,
    height: 1200,
    alt: img.alt || "",
  }));
});

const arrowsEnter = computed(() => {
  return images.value.length > 5;
});

function onMainMounted(splide) {
  mainInstance.value = splide;
  trySync();
}

function onThumbMounted(splide) {
  thumbInstance.value = splide;
  trySync();
}

function trySync() {
  if (mainInstance.value && thumbInstance.value) {
    mainInstance.value.sync(thumbInstance.value);
  }
}

let lightbox;

onMounted(() => {
  lightbox = new PhotoSwipeLighbox({
    dataSource: lightboxImages.value,
    pswpModule: () => import("photoswipe"),
    //Preload HD
    preload: [2, 3],
    //Zoom control
    initialZoomLevel: "fit",
    secondaryZoomLevel: 2,
    maxZoomLevel: 4,
    //RTL
    bgOpacity: 0.9,

    showHideAnimationType: "zoom",
  });
  lightbox.on("uiRegister", () => {
    lightbox.pswp.ui.registerElement({
      name: "thumbnails",
      order: 10,
      isButton: false,
      appendTo: "root",
      html: `
        <div class="pswp__thumbnail">
        ${images.value
          .filter((i) => i.type !== "video")
          .map((img, i) => `<img src="${img.image}" data-index="${i}" />`)
          .join("")}
      </div>
    `,
      onInit: (el, pswp) => {
        el.querySelectorAll("img").forEach((img) => {
          img.onclick = () => {
            pswp.goTo(Number(img.dataset.index));
          };
        });

        pswp.on("change", () => {
          el.querySelectorAll("img").forEach((img, i) => {
            img.classList.toggle("active", i === pswp.currIndex);
          });
        });

        const active = el.querySelector(".pswp__thumbnail img.active");
        if (active) {
          active.scrollIntoView({
            behavior: "smooth",
            inline: "center",
          });
        }
      },
    });
  });
  lightbox.on("afterInit", () => {
    document.querySelector(".pswp")?.setAttribute("dir", "rtl");
  });
  lightbox.on("uiRegister", () => {
    lightbox.pswp.ui.registerElement({
      name: "fullscreen",
      order: 6,
      isButton: true,
      appendTo: "top-bar",
      html: "⛶",
      onClick: () => {
        if (!document.fullscreenElement) {
          document.documentElement.requestFullscreen();
        } else {
          document.exitFullscreen();
        }
      },
    });
  });
  lightbox.init();
});

const openLightbox = (index) => {
  lightbox.loadAndOpen(index);
};

watch(lightboxImages, (newImages) => {
  if (lightbox && newImages.length) {
    lightbox.options.dataSource = newImages;
  }
});
</script>

<style>
.product-images {
  position: relative;
  border-radius: 10px;
}

.tag {
  font-size: 1rem;
}

.product-card__badges {
  top: 16px;
  right: 8px;
  z-index: 10;
}

.countdown {
  margin-top: 8px;
  margin-left: 8px;
}

.thumb-image {
  height: 80px;
  width: 80px;
  object-fit: cover;
  cursor: pointer;
}

/* Splide Custom Css */

.gallery__thumbs .splide__list {
  justify-content: center;
}

.gallery__thumbs
  .splide__track--nav
  > .splide__list
  > .splide__slide.is-active {
  border: 2px solid var(--c-navy);
  text-align: center;
  padding: 3px;
}
</style>