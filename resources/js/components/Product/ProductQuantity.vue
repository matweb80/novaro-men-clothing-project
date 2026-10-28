<script setup>
import { toFa } from "@/utils/formatPrice";
import { computed, onMounted } from "vue";
import { useCart } from "../../composables/useCart";

const props = defineProps({
  product: { type: Object, required: true },
});

const {
  cart,
  isVisibleDialog,
  modal,
  loadCart,
  changeQuantity,
  removeFromCart,
} = useCart();

onMounted(() => {
  if (!cart.loaded) {
    loadCart();
  }
});

const items = Object.keys(cart.items).map((key) => cart.items[key]);
const item = items.find((i) => i.product.id == props.product.id);

const maxQty = computed(() => props.product?.inventory );
const canDecrement = computed(() => item.quantity > 1);
const canIncrement = computed(() => item.quantity < maxQty.value);

function increment() {
  if (canIncrement.value) item.quantity++;
}
function decrement() {
  if (canDecrement.value) item.quantity--;
}
</script>

<template>
  <div class="pdp-option">
    <p class="pdp-option__title" style="margin-bottom: var(--sp-3)">تعداد</p>
    <div class="qty-control qty-control--lg">
      <!-- <button
        type="button"
        aria-label="کاهش تعداد"
        :disabled="item.quantity == 1"
        @click="
          changeQuantity(
            item.id,
            item.quantity - 1,
            item.product.slug,
            'my-cart'
          )
        "
      >
        −
      </button>
      <select
        class="form-control text-center border-0 item-select"
        @change="
          changeQuantity(
            item.id,
            $event.target.value,
            item.product.slug,
            'my-cart'
          )
        "
      >
        <option
          v-for="index in item.product.inventory"
          :key="index"
          :value="index"
          :selected="item.quantity == index"
        >
          {{ toFa(index) }}
        </option>
      </select>
      <button
        type="button"
        aria-label="افزایش تعداد"
        :disabled="item.quantity == item.product.inventory"
        @click="
          changeQuantity(
            item.id,
            item.quantity + 1,
            item.product.slug,
            'my-cart'
          )
        "
      >
        +
      </button> -->
      <button type="button" aria-label="کاهش تعداد" :disabled="!canDecrement" @click="decrement">−</button>
      <span class="latin">{{ toFa(item.quantity) }}</span>
      <button type="button" aria-label="افزایش تعداد" :disabled="!canIncrement" @click="increment">+</button>
    </div>
  </div>
</template>
