<template>
  <a href="#main" class="skip-link">رفتن به محتوای اصلی</a>

  <AnnouncementBar />

  <TheHeader
    @open-mobile-nav="openMobileNav"
    @open-search="openSearch"
    @open-cart="openCart"
  />

  <SearchOverlay ref="searchOverlayRef" />

  <MobileNavigation ref="mobileNavRef" />

  <CartDrawer ref="cartDrawerRef" />

  <ShopView :category-slug="categorySlug" />

  <TheFooter />
</template>

<script setup>
import { ref } from "vue";
import AnnouncementBar from "@/components/layout/AnnouncementBar.vue";
import TheHeader from "@/components/layout/TheHeader.vue";
import SearchOverlay from "@/components/layout/SearchOverlay.vue";
import MobileNavigation from "@/components/layout/MobileNavigation.vue";
import CartDrawer from "@/components/layout/CartDrawer.vue";
import TheFooter from "@/components/layout/TheFooter.vue";
import ShopView from "@/views/ShopView.vue";

const props = defineProps(["categorySlug"]);

const mobileNavRef = ref(null);
const searchOverlayRef = ref(null);
const cartDrawerRef = ref(null);

function openMobileNav(triggerButton) {
  mobileNavRef.value?.open(triggerButton);
}
function openSearch(triggerButton) {
  searchOverlayRef.value?.open(triggerButton);
}
function openCart(triggerButton) {
  cartDrawerRef.value?.open(triggerButton);
}
</script>