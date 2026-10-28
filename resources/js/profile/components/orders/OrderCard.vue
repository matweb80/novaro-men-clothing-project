<script setup>
// reuse باشد (همان shape داده، فقط با جزئیات بیشتر روی صفحه‌ی جدا).
import { computed } from "vue";
import { toFa, formatToman } from "../../../utils/formatPrice";

const props = defineProps({
  order: { type: Object, required: true },
});

const ORDER_STATUS = {
  unpaid: { label: "در انتظار پرداخت", tone: "neutral" },
  preparation: { label: "در حال پردازش", tone: "navy-40" },
  paid: { label: "پرداخت شده", tone: "navy" },
  posted: { label: "ارسال شده", tone: "gold" },
  received: { label: "تحویل داده شده", tone: "success" },
  canceled: { label: "لغو شده", tone: "danger" },
};

const status = computed(
  () => ORDER_STATUS[props.order.status] ?? ORDER_STATUS.unpaid
);

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString("fa-IR", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}
</script>

<template>
  <article class="order-card">
    <header class="order-card__head">
      <div>
        <router-link
          :to="`/profile/orders/${order.id}`"
          class="order-card__code latin"
          >#{{ order.id }}</router-link
        >
        <p class="order-card__date">{{ toFa(formatDate(order.created_at)) }}</p>
      </div>
      <span class="order-status" :class="`order-status--${status.tone}`">{{
        status.label
      }}</span>
    </header>

    <ul class="order-card__items">
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
          <p class="order-item__name">{{ item.title }}</p>
          <p class="order-item__meta">{{ toFa(item.pivot.quantity) }}</p>
        </div>
      </li>
    </ul>

    <footer class="order-card__foot">
      <p class="order-card__total">
        جمع کل:
        <span class="latin" dir="ltr">{{ formatToman(order.price) }}</span>
      </p>
      <div class="order-actions-items">
        <router-link :to="`/profile/orders/${order.id}`" class="link-btn"
          >مشاهده جزئیات</router-link
        >
        <a
          v-if="order.status === 'unpaid'"
          :href="'/profile/orders/' + order.id + '/payment'"
          class="link-btn link-btn--danger"
          >پرداخت</a
        >
      </div>
    </footer>
  </article>
</template>

<style scoped>
.order-card {
  border: 1px solid var(--c-border);
  border-radius: var(--r-md);
  padding: var(--sp-5);
}
.order-card__head {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: var(--sp-3);
  padding-bottom: var(--sp-3);
  border-bottom: 1px solid var(--c-border);
}
.order-card__code {
  font-weight: 600;
}
.order-card__date {
  font-size: var(--fs-small);
  color: var(--c-text-muted);
  margin-top: 2px;
}

.order-status {
  font-size: var(--fs-caption);
  border-radius: var(--r-full);
  padding: 3px var(--sp-3);
  white-space: nowrap;
  border: 1px solid transparent;
}
.order-status--neutral {
  color: var(--c-text-muted);
  border-color: var(--c-border-strong);
}
.order-status--navy {
  color: var(--c-navy);
  border-color: var(--c-navy);
}
.order-status--navy-40 {
  color: var(--c-navy-40);
  border-color: var(--c-navy-40);
}
.order-status--gold {
  color: var(--c-gold-hover);
  border-color: var(--c-gold);
}
.order-status--success {
  color: var(--c-success);
  border-color: var(--c-success);
}
.order-status--danger {
  color: var(--c-danger);
  border-color: var(--c-danger);
}

.order-card__items {
  padding-block: var(--sp-2);
}
.order-item__info {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 4px;
}
.order-item__name {
  font-size: var(--fs-small);
  font-weight: 600;
}
.order-item__meta {
  font-size: var(--fs-caption);
  color: var(--c-text-muted);
}

.order-card__foot {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-top: var(--sp-3);
  border-top: 1px solid var(--c-border);
}
.order-card__total {
  font-size: var(--fs-small);
  font-weight: 600;
}
.link-btn {
  background: none;
  border: none;
  padding: var(--sp-1) var(--sp-2);
  cursor: pointer;
  font-size: var(--fs-small);
  font-family: var(--font);
  color: var(--c-navy);
  border: 1px var(--c-navy) solid;
  transition: background-color var(--dur-fast) var(--ease),
    color var(--dur-fast) var(--ease), border-color var(--dur-fast) var(--ease);
    text-decoration: none;
}
.link-btn:hover {
  background: var(--c-navy);
  color: var(--c-white);
}
.link-btn.link-btn--danger {
  color: var(--c-danger);
  border: 1px var(--c-danger) solid;
}
.link-btn.link-btn--danger:hover {
  background: var(--c-danger);
  color: var(--c-white);
}

.order-actions-items {
  display: flex;
  flex-wrap: wrap;
  gap: var(--sp-2);
}

/* custom */
.cart-item__media {
  height: auto;
  background: none;
}
</style>
