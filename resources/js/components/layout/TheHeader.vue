<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { useCart } from "@/composables/useCart";
import { toFa } from "@/utils/formatPrice";
import DesktopNavigation from "./DesktopNavigation.vue";
import BaseLogo from "../UI/BaseLogo.vue";
import { useCheckAppData } from "../../composables/useCheckAppData";

const emit = defineEmits(["open-mobile-nav", "open-search", "open-cart"]);

const { cart, loadCart } = useCart();
const { isAuthenticated } = useCheckAppData();
const accountHref = computed(() =>
  isAuthenticated.value.value ? "/profile" : "/login"
);
const isScrolled = ref(false);
function onScroll() {
  isScrolled.value = window.scrollY > 4;
}
onMounted(() => {
  window.addEventListener("scroll", onScroll, { passive: true });
  if (!cart.loaded) loadCart();
});
onUnmounted(() => window.removeEventListener("scroll", onScroll));
</script>

<template>
  <header class="site-header" :class="{ 'is-scrolled': isScrolled }">
    <div class="site-header__inner">
      <button
        type="button"
        class="icon-btn mobile-nav-trigger"
        aria-haspopup="dialog"
        aria-controls="mobileNav"
        aria-label="باز کردن منوی دسته‌بندی‌ها"
        @click="emit('open-mobile-nav', $event.currentTarget)"
      >
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <line x1="4" y1="7" x2="20" y2="7" />
          <line x1="4" y1="12" x2="20" y2="12" />
          <line x1="4" y1="17" x2="14" y2="17" />
        </svg>
      </button>

      <BaseLogo />

      <DesktopNavigation />

      <div class="header-actions">
        <button
          type="button"
          class="icon-btn"
          aria-haspopup="dialog"
          aria-controls="searchOverlay"
          aria-expanded="false"
          aria-label="جستجو"
          @click="emit('open-search', $event.currentTarget)"
        >
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="11" cy="11" r="7" />
            <line x1="21" y1="21" x2="16.5" y2="16.5" />
          </svg>
        </button>
        <a type="button" class="icon-btn" aria-label="حساب کاربری من" :href="accountHref">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="8" r="3.5" />
            <path d="M5 20c1.5-4 4.2-6 7-6s5.5 2 7 6" />
          </svg>
        </a>
        <button type="button" class="icon-btn" aria-label="لیست علاقه‌مندی‌ها">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path
              d="M12 20s-7-4.4-9.3-8.8C1.3 8 2.6 5 5.8 4.4c2-.4 3.7.5 4.9 2.1 1.2-1.6 2.9-2.5 4.9-2.1 3.2.6 4.5 3.6 3.1 6.8C19 15.6 12 20 12 20Z"
            />
          </svg>
        </button>
        <button
          type="button"
          class="icon-btn"
          aria-haspopup="dialog"
          aria-controls="cartDrawer"
          aria-label="سبد خرید"
          @click="emit('open-cart', $event.currentTarget)"
        >
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path
              d="M4 7h16l-1.5 11a2 2 0 0 1-2 1.7H7.5a2 2 0 0 1-2-1.7L4 7Z"
            />
            <path d="M8 7a4 4 0 0 1 8 0" />
          </svg>
          <span v-if="cart.totalCount" class="badge">{{
            toFa(cart.totalCount)
          }}</span>
        </button>
      </div>
    </div>
  </header>
</template>
