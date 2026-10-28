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

  <HomeView />

  <TheFooter />
</template>

<script setup>
import { onMounted, ref } from "vue";
import AnnouncementBar from "@/components/layout/AnnouncementBar.vue";
import TheHeader from "@/components/layout/TheHeader.vue";
import SearchOverlay from "@/components/layout/SearchOverlay.vue";
import MobileNavigation from "@/components/layout/MobileNavigation.vue";
import CartDrawer from "@/components/layout/CartDrawer.vue";
import TheFooter from "@/components/layout/TheFooter.vue";
import HomeView from "@/views/HomeView.vue";
import { http } from "@/utils/http";

const categories = ref([]);

onMounted(async () => {
  try {
    const response = await http("/api/categories");
    if (response.ok) categories.value = await response.json();
  } catch (error) {
    console.error("Unable to load categories", error);
  }
});

console.log(categories);

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
