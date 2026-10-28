<script setup>
import { reactive } from "vue";

const FAQ_GROUPS = [
  {
    id: "orders",
    title: "سفارش و پرداخت",
    items: [
      {
        id: "track-order",
        question: "چطور می‌توانم وضعیت سفارشم را پیگیری کنم؟",
        answer:
          "بعد از ثبت سفارش، می‌توانید از بخش «سفارش‌ها» در پروفایل کاربری خود، وضعیت و جزئیات هر سفارش را در هر زمان مشاهده کنید.",
        placeholder: false,
      },
      {
        id: "payment-methods",
        question: "چه روش‌های پرداختی پشتیبانی می‌شود؟",
        answer: "این بخش هنوز توسط تیم نوواررو تکمیل نشده است.",
        placeholder: true,
      },
      {
        id: "cancel-order",
        question: "آیا امکان لغو سفارش بعد از ثبت وجود دارد؟",
        answer: "این بخش هنوز توسط تیم نوواررو تکمیل نشده است.",
        placeholder: true,
      },
    ],
  },
  {
    id: "shipping",
    title: "ارسال و تحویل",
    items: [
      {
        id: "shipping-cost-time",
        question: "هزینه و زمان ارسال چقدر است؟",
        answer: "این بخش هنوز توسط تیم نوواررو تکمیل نشده است.",
        placeholder: true,
      },
      {
        id: "shipping-coverage",
        question: "آیا امکان ارسال به همه‌ی شهرها وجود دارد؟",
        answer: "این بخش هنوز توسط تیم نوواررو تکمیل نشده است.",
        placeholder: true,
      },
    ],
  },
  {
    id: "returns",
    title: "مرجوعی و تعویض",
    items: [
      {
        id: "return-process",
        question: "چطور می‌توانم کالا را مرجوع یا تعویض کنم؟",
        answer: "این بخش هنوز توسط تیم نوواررو تکمیل نشده است.",
        placeholder: true,
      },
      {
        id: "return-window",
        question: "مهلت مرجوعی کالا چند روز است؟",
        answer: "این بخش هنوز توسط تیم نوواررو تکمیل نشده است.",
        placeholder: true,
      },
    ],
  },
  {
    id: "sizing",
    title: "اندازه و نگهداری",
    items: [
      {
        id: "size-guide",
        question: "چطور سایز مناسب خودم را انتخاب کنم؟",
        answer: "این بخش هنوز توسط تیم نوواررو تکمیل نشده است.",
        placeholder: true,
      },
    ],
  },
];

const openIds = reactive(new Set());

function toggle(id) {
  if (openIds.has(id)) openIds.delete(id);
  else openIds.add(id);
}
</script>

<template>
  <section class="faq container" id="faq">
    <p class="eyebrow">راهنما</p>
    <h1 class="page-hero__title">سوالات متداول</h1>
    <div v-for="group in FAQ_GROUPS" :key="group.id" class="faq-group">
      <h2 class="faq-group__title">{{ group.title }}</h2>
      <div class="faq-list">
        <div v-for="item in group.items" :key="item.id" class="faq-item">
          <button
            :id="`faqBtn-${item.id}`"
            type="button"
            class="faq-item__trigger"
            :aria-expanded="openIds.has(item.id)"
            :aria-controls="`faqPanel-${item.id}`"
            @click="toggle(item.id)"
          >
            <span>{{ item.question }}</span>
            <svg
              class="faq-item__chevron"
              :class="{ 'faq-item__chevron--open': openIds.has(item.id) }"
              viewBox="0 0 24 24"
              aria-hidden="true"
            >
              <path d="m6 9 6 6 6-6" />
            </svg>
          </button>
          <div
            v-show="openIds.has(item.id)"
            :id="`faqPanel-${item.id}`"
            class="faq-item__panel"
            role="region"
            :aria-labelledby="`faqBtn-${item.id}`"
          >
            <p :class="{ 'faq-item__answer--placeholder': item.placeholder }">
              {{ item.answer }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>