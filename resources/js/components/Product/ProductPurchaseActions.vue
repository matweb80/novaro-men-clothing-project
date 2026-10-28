<script setup>
import { useWishlist } from "@/composables/useWishlist";
import { computed, onMounted } from "vue";
import { useCart } from "../../composables/useCart";

const props = defineProps({
  product: { type: Object, required: true },
});

const { cart, addToCart, loadCart, isVisibleDialog, modal, isLoading } =
  useCart();

onMounted(() => {
  if (!cart.loaded) {
    loadCart();
  }
});

const quantityInCart = computed(() => {
  const items = Object.keys(cart.items).map((key) => cart.items[key]);
  const item = items.find((i) => i.product.id == props.product.id);
  return item ? item.quantity : 0;
});

const isOutOfStock = computed(() => {
  return (
    props.product.inventory <= quantityInCart.value ||
    props.product.inventory <= 0
  );
});

const label = computed(() => {
  if (isLoading.value && !isOutOfStock.value) return "در حال افزودن…";
  if (isOutOfStock.value) return "محصول ناموجود";
  return "افزودن به سبد";
});
const { has, toggle } = useWishlist();
</script>

<template>
  <!-- mini dialog -->
  <base-mini-dialog
    :show="isVisibleDialog"
    :message="modal.message"
    :type="modal.type"
    @close="isVisibleDialog = false"
  ></base-mini-dialog>
  <div class="pdp-actions">
    <button
      type="button"
      class="btn btn--primary"
      :disabled="isOutOfStock || isLoading"
      @click="addToCart(product)"
    >
      {{ label }}
    </button>

    <button
      type="button"
      class="pdp-wishlist-btn"
      :aria-pressed="has(product.id) ? 'true' : 'false'"
      :aria-label="
        has(product.id) ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها'
      "
      @click="toggle(product.id)"
    >
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path
          d="M12 20s-7-4.4-9.3-8.8C1.3 8 2.6 5 5.8 4.4c2-.4 3.7.5 4.9 2.1 1.2-1.6 2.9-2.5 4.9-2.1 3.2.6 4.5 3.6 3.1 6.8C19 15.6 12 20 12 20Z"
        />
      </svg>
    </button>
  </div>
</template>
