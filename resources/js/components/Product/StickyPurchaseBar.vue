<script setup>
import { formatToman } from "@/utils/formatPrice";
import { computed, onMounted } from "vue";
import { useCart } from "../../composables/useCart";

const props = defineProps({
  product: { type: Object, required: true },
  isVisible: { type: Boolean, required: true },
});

const { cart, addToCart, loadCart, isVisibleDialog, modal, isLoading } =
  useCart();

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

onMounted(() => {
  if (!cart.loaded) {
    loadCart();
  }
});
</script>

<template>
  <!-- mini dialog -->
  <base-mini-dialog
    :show="isVisibleDialog"
    :message="modal.message"
    :type="modal.type"
    @close="isVisibleDialog = false"
  ></base-mini-dialog>
  <div class="pdp-sticky-bar" :class="{ 'is-visible': isVisible }">
    <div class="pdp-sticky-bar__info">
      <span class="pdp-sticky-bar__name">{{ product.title }}</span>
      <span class="pdp-sticky-bar__price latin" style="direction: rtl">{{
        formatToman(product.is_special ? product.sale_price : product.price)
      }}</span>
    </div>
    <button
      type="button"
      class="btn btn--primary btn--sm"
      :disabled="isOutOfStock || isLoading"
      @click="addToCart(product)"
    >
      {{ label }}
    </button>
  </div>
</template>
