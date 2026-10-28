<script setup>
import { computed, onMounted, reactive, ref, watch } from "vue";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";
import Discount from "@/components/partials/Discount.vue";
import locations from "@/data/locations.json";

import multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";

import { useCart } from "@/composables/useCart";
import { useCheckout } from "@/composables/useCheckout";
import { formatToman, toFa } from "@/utils/formatPrice";

const { cart, loadCart } = useCart();
const {
  form,
  originalAddress,
  userAddressLoaded,
  fieldErrors,
  formError,
  saving,
  loadAddress,
  saveAddress,
} = useCheckout();

const payForm = ref(null);
const paying = ref(false);

const provinces = ref(locations);
const cities = ref([]);

const clientErrors = reactive({});

const isVisibleDialog = ref(false);
const modal = ref({
  message: "",
  type: "",
});

const isVisibleDiscount = ref(false);

onMounted(async () => {
  await loadAddress();

  if (!cart.loaded) loadCart();

  if (cart.totalDiscountPrice) {
    isVisibleDiscount.value = true;
  }
});

const provinceOptions = computed(() => {
  return provinces.value.map((p) => ({
    id: p.province,
    name: p.province,
  }));
});

const cityOptions = computed(() => {
  const province = provinces.value.find(
    (p) => p.province === form.value.province_id
  );
  return province
    ? province.cities.map((c) => ({
        id: c,
        name: c,
      }))
    : [];
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

const hasStockIssue = computed(() =>
  lines.value.some(
    (i) => i.product.inventory <= 0 || i.quantity > i.product.inventory
  )
);
const canPay = computed(
  () =>
    cart.loaded &&
    !isEmpty.value &&
    !hasStockIssue.value &&
    !paying.value &&
    !saving.value
);

/* ----- اعتبارسنجی UX سمت کلاینت (مرجع نهایی: بک‌اند) ----- */
function validate() {
  Object.keys(clientErrors).forEach((k) => delete clientErrors[k]);
  if (form.value.billing_full_name.trim().length < 2)
    clientErrors.billing_full_name = "نام گیرنده را وارد کنید.";
  if (!form.value.billing_mobile) {
    clientErrors.billing_mobile = "شماره موبایل را وارد کنید";
  } else if (!/^09\d{9}$/.test(form.value.billing_mobile.trim()))
    clientErrors.billing_mobile =
      "شماره موبایل را به‌صورت ۰۹۱۲۳۴۵۶۷۸۹ وارد کنید.";
  if (
    !form.value.province_id ||
    form.value.province_id === undefined ||
    form.value.province_id === "" ||
    form.value.province_id === null
  )
    clientErrors.province_id = "استان را وارد کنید.";
  if (
    !form.value.city_id ||
    form.value.city_id === undefined ||
    form.value.city_id === "" ||
    form.value.city_id === null
  )
    clientErrors.city_id = "شهر را وارد کنید.";
  if (form.value.billing_address.trim().length < 5)
    clientErrors.billing_address = "آدرس کامل را وارد کنید.";
  if (
    form.value.billing_postal_code.trim() &&
    form.value.billing_postal_code.trim().length !== 10
  ) {
    clientErrors.billing_postal_code = "کد پستی باید ۱۰ رقم باشد.";
  }
  return Object.keys(clientErrors).length === 0;
}
const err = (field) => clientErrors[field] || fieldErrors.value[field] || "";

async function handlePay() {
  if (!canPay.value || !validate()) return;
  paying.value = true;
  const ok = await saveAddress({ ...form.value });
  if (!ok) {
    paying.value = false;
    return;
  }
  payForm.value.submit(); // مرورگر به درگاه/نتیجه هدایت می‌شود
}

watch(
  () => form.value.province_id,
  (provinceName) => {
    const province = provinces.value.find((p) => p.province == provinceName);
    cities.value = province ? province.cities : [];
    form.value.city_id = "";
  }
);

watch(
  [provinceOptions, originalAddress],
  ([options, data]) => {
    if (!options.length || !data) return;
    form.value.province_id = data.province_id;
    form.value.city_id = data.city_id;
  },
  { immediate: true }
);

// delete validate errors by changing inputs
watch(
  () => form.value.billing_full_name,
  () => {
    delete clientErrors.billing_full_name;
  }
);
watch(
  () => form.value.billing_mobile,
  () => {
    delete clientErrors.billing_mobile;
  }
);
watch(
  () => form.value.province_id,
  () => {
    delete clientErrors.province_id;
  }
);
watch(
  () => form.value.city_id,
  () => {
    delete clientErrors.city_id;
  }
);
watch(
  () => form.value.billing_address,
  () => {
    delete clientErrors.billing_address;
  }
);
watch(
  () => form.value.billing_postal_code,
  () => {
    delete clientErrors.billing_postal_code;
  }
);
</script>

<template>
  <main id="main">
    <div class="container co-top">
      <Breadcrumb current-label="تسویه حساب" />
      <h1 class="co-title">تسویه حساب</h1>
    </div>

    <div v-if="!cart.loaded" class="container co-note">در حال بارگذاری…</div>

    <div v-else-if="isEmpty" class="container shop-empty">
      <p class="shop-empty__title">سبد خرید شما خالی است</p>
      <p class="shop-empty__text">
        برای ادامه، ابتدا محصولی به سبد اضافه کنید.
      </p>
      <a href="/products" class="btn btn--primary btn--sm">مشاهده فروشگاه</a>
    </div>

    <div v-else class="container co-layout">
      <div class="co-main">
        <!-- ۱. آدرس ارسال -->
        <section class="co-section" aria-labelledby="coAddress">
          <h2 id="coAddress" class="co-section__title">آدرس ارسال</h2>
          <div class="co-grid">
            <div class="form-group">
              <label class="form-label" for="coName">نام گیرنده</label>
              <input
                id="coName"
                v-model="form.billing_full_name"
                class="form-input"
                autocomplete="name"
                :aria-invalid="!!err('billing_full_name')"
              />
              <p v-if="err('billing_full_name')" class="form-error">
                {{ err("billing_full_name") }}
              </p>
            </div>
            <div class="form-group">
              <label class="form-label" for="coMobile">موبایل</label>
              <input
                id="coMobile"
                v-model="form.billing_mobile"
                type="tel"
                class="form-input latin"
                dir="ltr"
                autocomplete="tel"
                :aria-invalid="!!err('billing_mobile')"
              />
              <p v-if="err('billing_mobile')" class="form-error">
                {{ err("billing_mobile") }}
              </p>
            </div>
            <div class="form-group">
              <label class="form-label" for="coProvince">استان</label>
              <multiselect
                id="coProvince"
                v-model="form.province_id"
                placeholder="انتخاب استان"
                searchable
                :options="provinceOptions"
                dir="rtl"
                valueProp="id"
                label="name"
                autocomplete="new-password"
                class="form-input checkout-select"
                :aria-invalid="!!err('province_id')"
              />
              <p v-if="err('province_id')" class="form-error">
                {{ err("province_id") }}
              </p>
            </div>
            <div class="form-group">
              <label class="form-label" for="coCity">شهر</label>
              <multiselect
                id="coCity"
                v-model="form.city_id"
                placeholder="انتخاب شهر"
                searchable
                :options="cityOptions"
                :disabled="!cities.length"
                dir="rtl"
                valueProp="id"
                label="name"
                autocomplete="new-password"
                class="form-input checkout-select"
                :aria-invalid="!!err('city_id')"
              />
              <p v-if="err('city_id')" class="form-error">
                {{ err("city_id") }}
              </p>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label" for="coAddressLine">آدرس کامل</label>
            <textarea
              id="coAddressLine"
              v-model="form.billing_address"
              class="form-input form-textarea"
              rows="3"
              :aria-invalid="!!err('billing_address')"
            ></textarea>
            <p v-if="err('billing_address')" class="form-error">
              {{ err("billing_address") }}
            </p>
          </div>
          <div class="form-group co-postal">
            <label class="form-label" for="coPostal"
              >کد پستی <span class="form-optional">(اختیاری)</span></label
            >
            <input
              id="coPostal"
              v-model="form.billing_postal_code"
              class="form-input latin"
              dir="ltr"
              inputmode="numeric"
              :aria-invalid="!!err('billing_postal_code')"
            />
            <p v-if="err('billing_postal_code')" class="form-error">
              {{ err("billing_postal_code") }}
            </p>
          </div>
          <div class="form-group">
            <label class="form-label" for="coExtraDescription">توضیحات</label>
            <textarea
              id="coExtraDescription"
              v-model="form.billing_extra_description"
              class="form-input form-textarea"
              placeholder="اگر نکته ای هست با ما درمیان بگذارید(مثال: موارد هنگام تحویل کالا)"
              rows="3"
            ></textarea>
          </div>
          <p v-if="formError" class="form-error" role="alert">
            {{ formError }}
          </p>
        </section>

        <!-- ۲. پرداخت -->
        <section class="co-section" aria-labelledby="coPayment">
          <h2 id="coPayment" class="co-section__title">پرداخت</h2>
          <p class="co-readonly co-readonly--muted">
            پس از ثبت سفارش، برای پرداخت به درگاه پرداخت منتقل می‌شوید.
          </p>
        </section>
      </div>

      <!-- خلاصه سفارش -->
      <aside class="co-summary" aria-label="خلاصه سفارش">
        <h2 class="co-summary__title">خلاصه سفارش</h2>

        <ul class="co-lines">
          <li v-for="item in lines" :key="item.key" class="co-line">
            <div class="co-line__media">
              <img
                v-if="item.product.image"
                :src="item.product.image"
                :alt="item.product.title"
                loading="lazy"
              />
            </div>
            <div class="co-line__info">
              <p class="co-line__name">
                {{ item.product.title
                }}<span
                  v-if="item.product.is_special"
                  class="co-line__name-special"
                >
                  (محصول فروش ویژه)</span
                >
              </p>
              <p class="co-line__meta">تعداد: {{ toFa(item.quantity) }}</p>
              <p
                v-if="
                  item.product.inventory <= 0 ||
                  item.quantity > item.product.inventory
                "
                class="form-error"
              >
                موجودی کافی نیست — سبد خرید را اصلاح کنید
              </p>
            </div>
            <p class="co-line__price latin" dir="ltr">
              {{ formatToman(lineSubtotal(item)) }}
            </p>
          </li>
        </ul>

        <div class="co-row">
          <span>جمع جزء</span
          ><span class="latin" dir="ltr">{{ formatToman(subtotal) }}</span>
        </div>

        <Discount />

        <div class="co-row co-row--special" v-if="specialSaleValue > 0">
          <span>فروش ویژه</span>
          <span class="latin" dir="ltr"
            >−{{ formatToman(specialSaleValue) }}</span
          >
        </div>
        <div v-if="cart.totalDiscountPrice" class="co-row co-row--discount">
          <span>تخفیف</span
          ><span class="latin" dir="ltr"
            >−{{ formatToman(discountValue) }}</span
          >
        </div>
        <div class="co-row co-row--total">
          <span>مبلغ قابل پرداخت</span
          ><span class="latin" dir="ltr">{{ formatToman(total) }}</span>
        </div>
        <p class="co-note-small">
          هزینه ارسال (در صورت وجود) و مبلغ نهایی توسط سرور تأیید می‌شود.
        </p>

        <button
          type="button"
          class="btn btn--primary btn--block"
          :disabled="!canPay"
          @click="handlePay"
        >
          {{ paying || saving ? "در حال ثبت سفارش…" : "ثبت سفارش و پرداخت" }}
        </button>
        <a href="/cart" class="co-back">بازگشت به سبد خرید</a>
      </aside>
    </div>

    <!-- فرم پرداخت native — هیچ مبلغی ارسال نمی‌شود -->
    <form ref="payForm" method="POST" action="/payment" hidden>
      <input type="hidden" name="_token" :value="csrf" />
    </form>
  </main>
</template>

<style scoped>
.co-top {
  padding-top: var(--sp-6);
  padding-bottom: var(--sp-4);
}
.co-title {
  font-size: var(--fs-h2);
  font-weight: 500;
  margin-top: var(--sp-2);
}
.co-note {
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

/* موبایل: ابتدا فرم‌ها، سپس خلاصه؛ دسکتاپ: دو ستون با خلاصه‌ی چسبان */
.co-layout {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--sp-8);
  padding-bottom: var(--sp-20);
}
@media (min-width: 992px) {
  .co-layout {
    grid-template-columns: 1.4fr 1fr;
    align-items: start;
  }
}

.co-section {
  border: 1px solid var(--c-border);
  border-radius: var(--r-md);
  padding: var(--sp-6);
  margin-bottom: var(--sp-5);
  background: var(--c-white);
}
.co-section__title {
  font-size: var(--fs-body-lg);
  font-weight: 600;
  margin-bottom: var(--sp-4);
}
.co-readonly {
  font-size: var(--fs-body);
}
.co-readonly--muted {
  color: var(--c-text-muted);
  font-size: var(--fs-small);
}
.link-btn {
  display: inline-block;
  margin-top: var(--sp-2);
  font-size: var(--fs-small);
  color: var(--c-navy);
  text-decoration: underline;
}

.co-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--sp-4);
}
@media (min-width: 576px) {
  .co-grid {
    grid-template-columns: 1fr 1fr;
  }
}
.co-postal {
  max-width: 240px;
}

/* الگوی فرم — مثل Phase 1/3 محلی تعریف شده (فایل global مشترکی تأیید نشده) */
.form-group {
  display: flex;
  flex-direction: column;
  gap: var(--sp-2);
  margin-bottom: var(--sp-4);
}
.form-label {
  font-size: var(--fs-small);
  font-weight: 600;
}
.form-optional {
  font-weight: 400;
  color: var(--c-text-muted);
}
.form-input {
  width: 100%;
  padding: var(--sp-3) var(--sp-4);
  border: 1px solid var(--c-border-strong);
  border-radius: var(--r-sm);
  background: var(--c-white);
  color: var(--c-text);
  font-size: var(--fs-body);
  font-family: var(--font);
}
.form-input:focus {
  border-color: var(--c-navy);
}
.form-input[aria-invalid="true"] {
  border-color: var(--c-danger);
}
.form-textarea {
  resize: vertical;
  min-height: 90px;
}
.form-error {
  font-size: var(--fs-small);
  color: var(--c-danger);
}

.co-summary {
  border: 1px solid var(--c-border);
  border-radius: var(--r-md);
  padding: var(--sp-6);
  background: var(--c-white);
}
@media (min-width: 992px) {
  .co-summary {
    position: sticky;
    top: var(--sp-8);
  }
}
.co-summary__title {
  font-weight: 600;
  margin-bottom: var(--sp-4);
}
.co-lines {
  margin-bottom: var(--sp-4);
}
.co-line {
  display: flex;
  align-items: center;
  gap: var(--sp-3);
  padding-block: var(--sp-3);
  border-bottom: 1px solid var(--c-border);
}
.co-line__media {
  width: 48px;
  height: 60px;
  border-radius: var(--r-sm);
  background: #edeff3;
  overflow: hidden;
  flex-shrink: 0;
}
.co-line__media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.co-line__info {
  flex: 1;
}
.co-line__name {
  font-size: var(--fs-small);
  font-weight: 600;
}
.co-line__name-special {
  font-size: var(--fs-caption);
  color: var(--c-danger);
}
.co-line__meta {
  font-size: var(--fs-caption);
  color: var(--c-text-muted);
}
.co-line__price {
  font-size: var(--fs-small);
  font-weight: 600;
}

.co-row {
  display: flex;
  justify-content: space-between;
  font-size: var(--fs-small);
  color: var(--c-text-muted);
  margin-bottom: var(--sp-2);
}
.co-row--special {
  color: var(--c-danger);
}
.co-row--discount {
  color: var(--c-success);
}
.co-row--total {
  font-size: var(--fs-body-lg);
  font-weight: 700;
  color: var(--c-text);
  padding-top: var(--sp-3);
  border-top: 1px solid var(--c-border);
  margin-top: var(--sp-3);
}
.co-note-small {
  font-size: var(--fs-caption);
  color: var(--c-text-muted);
  margin-block: var(--sp-3) var(--sp-5);
}
.co-back {
  display: block;
  text-align: center;
  margin-top: var(--sp-4);
  font-size: var(--fs-small);
  color: var(--c-text-muted);
  text-decoration: underline;
}

.checkout-select {
  --ms-border-color: rgb(118, 118, 118);
  --ms-radius: var(--r-sm);
  --ms-px: 8px;
  --ms-ring-color: transparent;
  --ms-option-bg-selected-pointed: var(--c-gold-hover);
  --ms-option-bg-selected: var(--c-gold);
}

div.checkout-select:not(.is-open)
  > :is(.multiselect-wrapper)
  > :is(.multiselect-search) {
  cursor: pointer !important;
}

.checkout-select.is-disabled {
  background-color: #f9fafb;
  opacity: 0.6;
  cursor: not-allowed;
}
.checkout-select.is-disabled > :is(.multiselect-wrapper) {
  cursor: not-allowed;
}

.checkout-select.multiselect.is-active {
  box-shadow: 0 0 0 var(--ms-ring-width, 3px)
    var(--ms-ring-color, rgba(13, 110, 253, 0.188)) !important;
}

.checkout-select.has-error {
  --ms-border-color: #ef4444;
  --ms-ring-color: #ef4444;
}
</style>
