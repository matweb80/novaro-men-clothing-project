<template>
  <section v-if="!isLoading && order" class="order-details container">
    <h3 class="no-title">فاکتور فروش</h3>
    <h2 class="page-hero__title">
      {{ order ? `سفارش #${order.id}` : "جزئیات سفارش" }}
    </h2>
    <div class="order-details__grid">
      <div class="order-details__main">
        <!-- وضعیت و تاریخ -->
        <div class="order-summary-card">
          <div class="order-summary-card__row">
            <span class="order-summary-card__label">تاریخ ثبت سفارش</span>
            <span>{{ toFa(formatDate(order.created_at)) }}</span>
          </div>
          <div class="order-summary-card__row" v-if="order.tracking_serial">
            <span class="order-summary-card__label">کد پیگیری</span>
            <span>{{ order.tracking_serial }}</span>
          </div>
          <div class="order-summary-card__row">
            <span class="order-summary-card__label">وضعیت</span>
            <span
              class="order-status"
              :class="`order-status--${status.tone}`"
              >{{ status.label }}</span
            >
          </div>
        </div>

        <!-- آیتم‌های سفارش -->
        <h2 class="order-details__section-title">اقلام سفارش</h2>
        <ul class="order-items">
          <li v-for="item in order.products" :key="item.id" class="cart-item">
            <div class="cart-item__media">
              <img
                v-if="item.image"
                :src="item.image"
                :alt="item.title"
                loading="lazy"
              />
            </div>
            <div class="order-item__info">
              <a
                :href="'/products/' + item.slug"
                target="blank"
                class="order-item__name"
                >{{ item.title }}</a
              >
              <p class="order-item__meta">
                تعداد: {{ toFa(item.pivot.quantity) }}
              </p>
            </div>
            <p class="order-item__price latin" dir="ltr">
              {{
                formatToman(
                  (item.sale_price ?? item.price) * item.pivot.quantity
                )
              }}
            </p>
          </li>
        </ul>

        <!-- جمع‌بندی مالی -->
        <div class="order-totals">
          <div class="order-totals__row">
            <span>جمع جزء</span>
            <span class="latin" dir="ltr">{{ formatToman(order.price) }}</span>
          </div>
          <div class="order-totals__row">
            <span>هزینه ارسال</span>
            <span class="latin" dir="ltr">{{
              formatToman(order.shippingCost ?? 0)
            }}</span>
          </div>
          <div v-if="order.discount" class="order-totals__row">
            <span>تخفیف</span>
            <span class="latin" dir="ltr"
              >−{{ formatToman(order.discount) }}</span
            >
          </div>
          <div class="order-totals__row order-totals__row--total">
            <span>جمع کل</span>
            <span class="latin" dir="ltr">{{
              formatToman(Number(order.price) + (order.shippingCost ?? 0))
            }}</span>
          </div>
        </div>
      </div>

      <!-- جزئیات صورتحساب -->
      <aside class="billing">
        <h2 class="order-details__section-title">اطلاعات گیرنده</h2>
        <div class="billing-card">
          <p class="billing-card__row">{{ address?.billing_full_name }}</p>
          <p class="billing-card__row latin" dir="ltr">
            {{ address?.billing_mobile }}
          </p>
        </div>

        <h2 class="order-details__section-title">آدرس تحویل</h2>
        <div class="billing-card">
          <p class="billing-card__row">
            {{ address?.province_id }}
            {{ address?.city_id }}
          </p>
          <p class="billing-card__row">
            {{ address?.billing_address }}
          </p>
          <p
            v-if="address?.billing_postal_code"
            class="billing-card__row latin"
            dir="ltr"
          >
            {{ address?.billing_postal_code }}
          </p>
          <p
            v-if="address?.billing_extra_description"
            class="billing-card__row latin"
          >
            {{ address?.billing_extra_description }}
          </p>
        </div>
        <button class="btn btn--outline no-print" @click="printInvoice">
          پرینت
        </button>
      </aside>
    </div>
  </section>
  <!-- سفارش پیدا نشد -->
  <section v-else-if="!isLoading && !order" class="empty-state container">
    <svg class="empty-state__icon" viewBox="0 0 24 24" aria-hidden="true">
      <rect x="4" y="7" width="16" height="13" rx="2" />
      <path d="M8 7V5a4 4 0 0 1 8 0v2" />
    </svg>
    <p class="empty-state__text">این سفارش پیدا نشد.</p>
    <router-link to="/profile" class="btn btn--outline btn--sm"
      >بازگشت به سفارش‌ها</router-link
    >
  </section>
  <!-- spinner -->
  <base-spinner v-if="isLoading"></base-spinner>
</template>

<script>
import { computed, onMounted } from "vue";
import { useOrders } from "../composables/useOrders";
import { toFa, formatToman } from "../../utils/formatPrice";

export default {
  props: ["id"],
  setup(props) {
    const { fetchOrderDetails, isLoading, order, orderProducts, address } =
      useOrders();
    onMounted(async () => {
      fetchOrderDetails(props.id, 1, false);
    });

    function printInvoice() {
      window.print();
    }

    function formatDate(dateStr) {
      return new Date(dateStr).toLocaleDateString("fa-IR", {
        year: "numeric",
        month: "long",
        day: "numeric",
      });
    }

    const ORDER_STATUS = {
      unpaid: { label: "در انتظار پرداخت", tone: "neutral" },
      preparation: { label: "در حال پردازش", tone: "navy-40" },
      paid: { label: "پرداخت شده", tone: "navy" },
      posted: { label: "ارسال شده", tone: "gold" },
      received: { label: "تحویل داده شده", tone: "success" },
      canceled: { label: "لغو شده", tone: "danger" },
    };

    const status = computed(() =>
      order.value
        ? ORDER_STATUS[order.value.status] ?? ORDER_STATUS.pending
        : null
    );

    return {
      isLoading,
      order,
      orderProducts,
      address,
      printInvoice,
      formatDate,
      status,
      toFa,
      formatToman,
    };
  },
};
</script>

<style>
@media print {
  body * {
    visibility: hidden;
  }
  .order-details,
  .order-details * {
    visibility: visible;
  }

  .order-details {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }

  .order-details .no-title {
    display: block;
    text-align: center;
  }

  a {
    text-decoration: none;
  }
}
</style>