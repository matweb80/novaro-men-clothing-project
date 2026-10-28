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

  <ProfilePage></ProfilePage>

  <TheFooter />
</template>

<script setup>
import { onMounted, provide, ref } from "vue";
import Dashboard from "./pages/Dashboard.vue";
import Orders from "./pages/Orders.vue";

import { useAuthStore } from "./stores/auth";
import ProfileLayout from "./components/layouts/ProfileLayout.vue";

import TheSidebar from "./components/layouts/TheSidebar.vue";
import NowTime from "./components/NowTime/NowTime.vue";

import AnnouncementBar from "@/components/layout/AnnouncementBar.vue";
import TheHeader from "@/components/layout/TheHeader.vue";
import SearchOverlay from "@/components/layout/SearchOverlay.vue";
import MobileNavigation from "@/components/layout/MobileNavigation.vue";
import CartDrawer from "@/components/layout/CartDrawer.vue";
import TheFooter from "@/components/layout/TheFooter.vue";
import ProfilePage from "@/components/pages/ProfilePage.vue";

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

const auth = useAuthStore();
onMounted(() => {
  if (!auth.user) {
    auth.fetchUser();
  }
});

provide("auth", auth);
</script>