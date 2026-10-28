<script setup>
import { useCart } from "@/composables/useCart";
import { useWishlist } from "@/composables/useWishlist";
import { formatToman, discountPercent, toFa } from "@/utils/formatPrice";
import { computed, onMounted, ref } from "vue";
import Countdown from "../Countdown/Countdown.vue";
import ImagePlate from "@/components/ui/ImagePlate.vue";

const props = defineProps({
  product: { type: Object, required: true },
});

const { cart, isVisibleDialog, modal, addToCart, loadCart } = useCart();
const remainingTime = ref("");

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

const isOnSale = computed(() => props.product.is_special);

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
  <div class="product-card">
    <article :data-id="product.id" role="listitem">
      <div class="product-card__media">
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

        <button
          type="button"
          class="icon-btn product-card__wishlist"
          :aria-pressed="has(product.id) ? 'true' : 'false'"
          :aria-label="`افزودن «${product.title}» به علاقه‌مندی‌ها`"
          @click.stop="toggle(product.id)"
        >
          <svg viewBox="0 0 24 24" aria-hidden="true" class="heart-fill">
            <path
              d="M12 20s-7-4.4-9.3-8.8C1.3 8 2.6 5 5.8 4.4c2-.4 3.7.5 4.9 2.1 1.2-1.6 2.9-2.5 4.9-2.1 3.2.6 4.5 3.6 3.1 6.8C19 15.6 12 20 12 20Z"
            />
          </svg>
        </button>

        <div>
          <ImagePlate
            class="cat-tile__media"
            :src="product.image ?? ''"
            :alt="product.title ?? ''"
            :placeholder-text="`NOVARO — ${product.slug}`"
          />
        </div>

        <div v-if="isOutOfStock" class="product-card__oos">
          <span>اتمام موجودی</span>
        </div>
      </div>

      <div class="product-card__body">
        <h3 class="product-card__name">
          <a :href="'/products/' + product.slug">{{ product.title }}</a>
        </h3>

        <div class="product-card__price">
          <span class="price" :class="{ 'price--sale': isOnSale }">
            <span class="latin" style="direction: rtl" v-if="isOnSale">{{
              formatToman(product.sale_price)
            }}</span>
            <span class="latin" style="direction: rtl" v-else>{{
              formatToman(product.price)
            }}</span>
          </span>
          <template v-if="isOnSale">
            <span class="price price--original">
              <span class="latin" style="direction: rtl">{{
                formatToman(product.price)
              }}</span>
            </span>
            <span class="price-discount">
              {{ toFa(discountPercent(product.sale_price, product.price)) }}٪
              تخفیف
            </span>
          </template>
        </div>

        <button
          v-if="!isOutOfStock"
          type="button"
          class="product-card__cta"
          @click="addToCart(product)"
        >
          <span aria-hidden="true">+</span>
          <span>افزودن به سبد</span>
        </button>
        <button
          v-else
          type="button"
          class="product-card__cta product-card__cta--outline"
        >
          اطلاع بده وقتی موجود شد
        </button>
      </div>
    </article>
  </div>
</template>

<style scoped>
.product-card {
  position: relative;
  box-shadow: 0px 0px 8px -5px rgba(0, 0, 0, 0.5);
  border-radius: 5px;
}
.product-card a {
  transition: all var(--dur-fast) ease-in;
}

.product-card__badges {
  top: 8px;
  right: 4px;
  z-index: 10;
}

.product-card .countdown {
  font-size: 0.6rem;
  font-weight: bold !important;
}

.product-card__media {
  border-radius: 5px 5px 0 0;
}
.product-card__body {
  padding: 0 6px 6px;
}
</style>
