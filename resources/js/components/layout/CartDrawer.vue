<script setup>
import { ref, nextTick, onMounted } from "vue";
import { useCart } from "@/composables/useCart";
import { useBodyScrollLock } from "@/composables/useBodyScrollLock";
import { useFocusTrap } from "@/composables/useFocusTrap";
import { formatToman, toFa } from "@/utils/formatPrice";

const isOpen = ref(false);
const drawerEl = ref(null);
const triggerEl = ref(null);

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
const { lock, unlock } = useBodyScrollLock();
const { trap, releaseTrap } = useFocusTrap();

function open(triggerButton) {
  triggerEl.value = triggerButton || null;
  isOpen.value = true;
  lock();
  nextTick(() => trap(drawerEl.value));
}

function close() {
  isOpen.value = false;
  unlock();
  releaseTrap();
  triggerEl.value?.focus();
}

function onKeydown(e) {
  if (e.key === "Escape") close();
}

defineExpose({ open, close });
</script>

<template>
  <div class="cart-backdrop" :class="{ 'is-visible': isOpen }" @click="close" />
  <aside
    ref="drawerEl"
    class="cart-drawer"
    :class="{ 'is-open': isOpen }"
    v-if="isOpen"
    role="dialog"
    aria-modal="true"
    aria-label="سبد خرید"
    @keydown="onKeydown"
  >
    <div class="cart-drawer__header">
      <p class="cart-drawer__title">سبد خرید</p>
      <button
        type="button"
        class="icon-btn"
        aria-label="بستن سبد خرید"
        @click="close"
      >
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <line x1="6" y1="6" x2="18" y2="18" />
          <line x1="18" y1="6" x2="6" y2="18" />
        </svg>
      </button>
    </div>

    <div class="cart-drawer__body">
      <div v-if="cart.items.length === 0" class="cart-empty">
        <span> سبد خرید شما خالی است.</span>
        <a href="/products" class="btn btn--outline">بازگشت به فروشگاه</a>
      </div>
      <div
        v-for="(item, qty) in cart.items"
        :key="item.product.id"
        class="cart-item"
      >
        <div class="cart-item__media">
          <img
            v-if="item.product.image"
            :src="item.product.image"
            :alt="item.product.title"
            loading="lazy"
          />
        </div>
        <div class="cart-item__body">
          <p class="cart-item__name">{{ item.product.title }}</p>
          <p class="cart-item__price latin" style="direction: rtl">
            {{ formatToman(item.product.price) }}
          </p>
          <div class="cart-item__row">
            <div class="qty-control">
              <button
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
              </button>
            </div>
            <button
              type="button"
              class="cart-item__remove"
              @click="removeFromCart(item.id)"
            >
              حذف
            </button>
          </div>
        </div>
        <!-- mini dialog -->
        <base-mini-dialog
          :show="isVisibleDialog"
          :message="modal.message"
          :type="modal.type"
          @close="isVisibleDialog = false"
        ></base-mini-dialog>
        <cart-summary-details
          v-if="cart.items.length !== 0"
        ></cart-summary-details>
      </div>
    </div>

    <div class="cart-drawer__footer">
      <template v-if="cart.items.length !== 0">
        <div class="cart-cart.totalPrice">
          <span>جمع کل</span>
          <span class="latin" style="direction: rtl">{{
            formatToman(cart.totalPrice)
          }}</span>
        </div>
        <a href="/cart" type="button" class="btn btn--outline btn--block">
          سبد خرید
        </a>
        <a href="/checkout" type="button" class="btn btn--primary btn--block">
          ادامه فرآیند خرید
        </a>
      </template>
    </div>
    <div class="visually-hidden" aria-live="polite">
      <!-- {{ cartStatus.message }} -->
    </div>
  </aside>
</template>

<style scoped>
select.item-select {
  width: 45px;
}

select.item-select:focus {
  box-shadow: none !important;
}

.cart-item__media {
  width: 80px;
  height: 100px;
  border-radius: var(--r-sm);
  background: #edeff3;
  overflow: hidden;
  flex-shrink: 0;
}
.cart-item__media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.cart-item__body button:disabled {
  cursor: not-allowed;
}
</style>