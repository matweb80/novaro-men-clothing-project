<script setup>
import { computed, onMounted, ref } from "vue";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";
import Discount from "@/components/partials/Discount.vue";
import { useCart } from "@/composables/useCart";
import { formatToman, toFa } from "@/utils/formatPrice";

const {
  cart,
  isLoading,
  isVisibleDialog,
  modal,
  loadCart,
  changeQuantity,
  removeFromCart,
} = useCart();

onMounted(() => {
  if (!cart.loaded) loadCart();
});

const lines = computed(() =>
  Object.entries(cart.items ?? {}).map(([key, item]) => ({ key, ...item }))
);
const isEmpty = computed(() => cart.loaded && lines.value.length === 0);

function lineUnitPrice(item) {
  let $price = null;
  if (item.product.is_special) {
    if (item.discount_percent) {
      $price = item.product.sale_price * (1 - item.discount_percent);
    } else {
      $price = item.product.sale_price;
    }
  } else if (item.discount_percent) {
    if (item.product.is_special) {
      $price = item.product.sale_price * (1 - item.discount_percent);
    } else {
      $price = item.product.price * (1 - item.discount_percent);
    }
  } else {
    $price = item.product.price;
  }
  return $price;
}
function lineSubtotal(item) {
  return lineUnitPrice(item) * item.quantity;
}

const setGroupedAttributes = (attribute_values) => {
  if (!attribute_values) return [];

  const grouped = {};
  for (const item of attribute_values) {
    const name = item.attribute.name;
    if (!grouped[name]) grouped[name] = [];
    if (!grouped[name].includes(item.value)) {
      grouped[name].push(item.value);
    }
  }
  return grouped;
};

const localSubtotal = computed(() =>
  lines.value.reduce((sum, item) => sum + lineSubtotal(item), 0)
);
const subtotal = computed(() => cart.oldTotalPrice ?? localSubtotal.value);
const total = computed(() => cart.totalPrice ?? subtotal.value);

const specialSaleValue = computed(() => {
  return cart.oldTotalPrice - cart.totalSpecialPrice;
});

const discountValue = computed(() => {
  return cart.totalSpecialPrice - cart.totalPrice;
});
</script>

<template>
  <main id="main">
    <div class="container cart-top">
      <Breadcrumb current-label="سبد خرید" />
      <h1 class="cart-title">سبد خرید</h1>
    </div>

    <div v-if="!cart.loaded" class="container cart-loading">
      در حال بارگذاری سبد خرید…
    </div>

    <!-- سبد خالی -->
    <div v-else-if="isEmpty" class="container shop-empty">
      <p class="shop-empty__title">سبد خرید شما خالی است</p>
      <p class="shop-empty__text">
        محصولات مورد علاقه‌تان را از فروشگاه اضافه کنید.
      </p>
      <a href="/products" class="btn btn--primary btn--sm">مشاهده فروشگاه</a>
    </div>

    <!-- سبد پر -->
    <div v-else class="container cart-layout">
      <ul class="cart-list">
        <li
          v-for="item in lines"
          :key="item.key"
          class="cart-line"
          :class="{ 'is-pending': isLoading }"
        >
          <div class="cart-item__media">
            <img
              v-if="item.product.image"
              :src="item.product.image"
              :alt="item.product.title"
              loading="lazy"
            />
          </div>

          <div class="cart-line__info">
            <a :href="`/products/${item.product.slug}`" class="cart-line__name"
              >{{ item.product.title }}
              <span v-if="item.product.is_special" class="cart-line__oos"
                >(محصول فروش ویژه)</span
              >
            </a>
            <p v-if="item.product.inventory <= 0" class="cart-line__oos">
              ناموجود شده
            </p>
            <small
              v-if="item.product.attribute_values"
              class="product-attributes"
            >
              <div
                v-for="(values, attrName, index) in setGroupedAttributes(
                  item.product.attribute_values
                )"
                :key="attrName"
              >
                <div v-if="index < 3">
                  <span class="text-muted">{{ attrName }}</span> :
                  <span class="text-black">{{ values.join(" , ") }}</span>
                </div>
              </div>
            </small>

            <div class="cart-line__price">
              <span class="latin" dir="ltr">{{
                formatToman(lineUnitPrice(item))
              }}</span>
              <span
                v-if="item.product.is_special || item.discount_percent"
                class="cart-line__original latin"
                dir="ltr"
              >
                {{ formatToman(item.product.price) }}
              </span>
            </div>

            <div class="qty-control qty-control--sm">
              <button
                type="button"
                :disabled="isLoading || item.quantity <= 1"
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
              <span class="latin">{{ toFa(item.quantity) }}</span>
              <button
                type="button"
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
            <p
              v-if="item.quantity >= item.product.inventory"
              class="cart-line__stock-note"
            >
              حداکثر موجودی همین تعداد است
            </p>
          </div>

          <div class="cart-line__end">
            <p class="cart-line__subtotal latin" dir="ltr">
              {{ formatToman(lineSubtotal(item)) }}
            </p>
            <button
              type="button"
              class="link-btn link-btn--danger"
              :disabled="isLoading"
              @click="removeFromCart(item.id)"
            >
              حذف
            </button>
          </div>
        </li>
      </ul>
      <!-- mini dialog -->
      <base-mini-dialog
        :show="isVisibleDialog"
        :message="modal.message"
        :type="modal.type"
        @close="isVisibleDialog = false"
      ></base-mini-dialog>

      <!-- خلاصه قیمت -->
      <aside class="cart-summary">
        <h2 class="cart-summary__title">خلاصه سفارش</h2>
        <div class="cart-summary__row">
          <span>جمع جزء</span>
          <span class="latin" dir="ltr">{{ formatToman(subtotal) }}</span>
        </div>
        <div class="cart-summary__row" v-if="specialSaleValue > 0">
          <span>فروش ویژه</span>
          <span class="latin" dir="ltr"
            >−{{ formatToman(specialSaleValue) }}</span
          >
        </div>
        <div class="cart-summary__row" v-if="cart.totalDiscountPrice">
          <span>تخفیف</span>
          <span class="latin" dir="ltr">−{{ formatToman(discountValue) }}</span>
        </div>
        <div class="cart-summary__row">
          <span>مجموع اقلام</span>
          <span class="latin" dir="ltr">{{ cart.totalCount }} عدد</span>
        </div>
        <div class="cart-summary__row cart-summary__row--total">
          <span>جمع کل</span>
          <span class="latin" dir="ltr">{{ formatToman(total) }}</span>
        </div>
        <p class="cart-summary__note">
          هزینه‌ی ارسال در مرحله‌ی تسویه‌حساب محاسبه می‌شود.
        </p>
        <a href="/checkout" class="btn btn--primary btn--block"
          >ادامه به تسویه‌حساب</a
        >
      </aside>
      <!-- Discount -->
      <Discount />
    </div>
  </main>
</template>

<style scoped>
.cart-top {
  padding-top: var(--sp-6);
  padding-bottom: var(--sp-4);
}
.cart-title {
  font-size: var(--fs-h2);
  font-weight: 500;
  margin-top: var(--sp-2);
}
.cart-loading {
  padding: var(--sp-16) 0;
  text-align: center;
  color: var(--c-text-muted);
}

.shop-empty {
  text-align: center;
  padding: var(--sp-20) var(--sp-4);
}
.shop-empty__title {
  font-weight: 600;
  margin-bottom: var(--sp-2);
}
.shop-empty__text {
  color: var(--c-text-muted);
  margin-bottom: var(--sp-5);
}

.cart-layout {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--sp-8);
  padding-bottom: var(--sp-20);
}
@media (min-width: 992px) {
  .cart-layout {
    grid-template-columns: 1.6fr 1fr;
    align-items: start;
  }
}

.cart-list {
  border-top: 1px solid var(--c-border);
}
.cart-line {
  display: flex;
  gap: var(--sp-4);
  padding: var(--sp-5) 0;
  border-bottom: 1px solid var(--c-border);
}
.cart-line.is-pending {
  opacity: 0.5;
  pointer-events: none;
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

.cart-line__info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: var(--sp-2);
}
.cart-line__name {
  font-weight: 600;
  color: var(--c-text);
  text-decoration: none;
}
.cart-line__oos {
  font-size: var(--fs-caption);
  color: var(--c-danger);
}
.cart-line__price {
  display: flex;
  gap: var(--sp-2);
  font-size: var(--fs-small);
}
.cart-line__original {
  color: var(--c-text-muted);
  text-decoration: line-through;
}
.cart-line__stock-note {
  font-size: var(--fs-caption);
  color: var(--c-gold-hover);
}

.qty-control--sm {
  display: flex;
  align-items: center;
  border: 1px solid var(--c-border-strong);
  border-radius: var(--r-sm);
  width: fit-content;
}
.qty-control--sm button {
  width: 30px;
  height: 30px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: var(--fs-body);
}
.qty-control--sm button:disabled {
  opacity: 0.4;
  cursor: default;
}
.qty-control--sm span {
  min-width: 24px;
  text-align: center;
  font-size: var(--fs-small);
}

.cart-line__end {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  justify-content: space-between;
}
.cart-line__subtotal {
  font-weight: 600;
}
.link-btn {
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
  font-size: var(--fs-small);
  font-family: var(--font);
  color: var(--c-danger);
  text-decoration: underline;
}

.cart-summary {
  border: 1px solid var(--c-border);
  border-radius: var(--r-md);
  padding: var(--sp-6);
  position: sticky;
  top: var(--sp-8);
}
.cart-summary__title {
  font-weight: 600;
  margin-bottom: var(--sp-4);
}
.cart-summary__row {
  display: flex;
  justify-content: space-between;
  font-size: var(--fs-small);
  color: var(--c-text-muted);
  margin-bottom: var(--sp-2);
}
.cart-summary__row--total {
  font-size: var(--fs-body-lg);
  font-weight: 700;
  color: var(--c-text);
  padding-top: var(--sp-3);
  border-top: 1px solid var(--c-border);
  margin-top: var(--sp-3);
}
.cart-summary__note {
  font-size: var(--fs-caption);
  color: var(--c-text-muted);
  margin-block: var(--sp-3) var(--sp-5);
}
</style>
