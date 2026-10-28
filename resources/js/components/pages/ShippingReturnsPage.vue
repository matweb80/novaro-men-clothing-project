<script setup>
import Breadcrumb from "../layout/Breadcrumb.vue";

const SHIPPING_CARDS = [
  {
    title: "زمان تحویل",
    text: "این بخش هنوز توسط تیم نوواررو تکمیل نشده است.",
    placeholder: true,
  },
  {
    title: "هزینه ارسال",
    text: "این بخش هنوز توسط تیم نوواررو تکمیل نشده است.",
    placeholder: true,
  },
  {
    title: "پیگیری سفارش",
    text: "بعد از ثبت سفارش، می‌توانید وضعیت ارسال را از بخش «سفارش‌ها» در پروفایل خود پیگیری کنید.",
    placeholder: false,
  },
];

const RETURN_CARDS = [
  {
    title: "شرایط مرجوعی",
    text: "این بخش هنوز توسط تیم نوواررو تکمیل نشده است.",
    placeholder: true,
  },
  {
    title: "مهلت مرجوعی",
    text: "این بخش هنوز توسط تیم نوواررو تکمیل نشده است.",
    placeholder: true,
  },
  {
    title: "روش بازگشت وجه",
    text: "این بخش هنوز توسط تیم نوواررو تکمیل نشده است.",
    placeholder: true,
  },
  {
    title: "ثبت درخواست مرجوعی",
    text: "ثبت درخواست مرجوعی مستقیماً از صفحه‌ی جزئیات سفارش، به‌زودی در بخش پروفایل فعال می‌شود.",
    placeholder: false,
  },
];
</script>

<template>
  <main id="main">
    <div class="page-hero container">
      <Breadcrumb current-label="ارسال و مرجوعی" />
      <p class="eyebrow">راهنما</p>
      <h1 class="page-hero__title">ارسال و مرجوعی</h1>
      <p class="page-hero__lead">
        هر آنچه باید درباره‌ی نحوه‌ی ارسال سفارش و مرجوع‌کردن کالا بدانید.
      </p>
    </div>

    <section class="policy container" aria-labelledby="shippingTitle">
      <h2 id="shippingTitle" class="policy__title">ارسال</h2>
      <div class="policy__grid">
        <article
          v-for="card in SHIPPING_CARDS"
          :key="card.title"
          class="policy-card"
        >
          <h3 class="policy-card__title">{{ card.title }}</h3>
          <p
            class="policy-card__text"
            :class="{ 'policy-card__text--placeholder': card.placeholder }"
          >
            {{ card.text }}
          </p>
        </article>
      </div>
    </section>

    <section class="policy container" aria-labelledby="returnsTitle">
      <h2 id="returnsTitle" class="policy__title">مرجوعی و تعویض</h2>
      <div class="policy__grid">
        <article
          v-for="card in RETURN_CARDS"
          :key="card.title"
          class="policy-card"
        >
          <h3 class="policy-card__title">{{ card.title }}</h3>
          <p
            class="policy-card__text"
            :class="{ 'policy-card__text--placeholder': card.placeholder }"
          >
            {{ card.text }}
          </p>
        </article>
      </div>
    </section>

    <section class="policy-cta container">
      <p class="policy-cta__text">سوالی فراتر از این‌هاست؟</p>
      <a href="/contact-us" class="btn btn--outline">تماس با ما</a>
    </section>
  </main>
</template>

<style scoped>
.page-hero {
  padding-top: var(--sp-10);
  padding-bottom: var(--sp-8);
  max-width: 640px;
}
.page-hero__title {
  font-size: var(--fs-h1);
  font-weight: 500;
  margin-block: var(--sp-2) var(--sp-3);
}
.page-hero__lead {
  color: var(--c-text-muted);
  font-size: var(--fs-body-lg);
}

.policy {
  padding-bottom: var(--sp-12);
}
.policy__title {
  font-size: var(--fs-h4);
  font-weight: 600;
  margin-bottom: var(--sp-5);
}
.policy__grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--sp-5);
}
@media (min-width: 576px) {
  .policy__grid {
    grid-template-columns: 1fr 1fr;
  }
}
@media (min-width: 992px) {
  .policy__grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

.policy-card {
  border: 1px solid var(--c-border);
  border-radius: var(--r-md);
  padding: var(--sp-5);
  background: var(--c-white);
}
.policy-card__title {
  font-size: var(--fs-body);
  font-weight: 600;
  margin-bottom: var(--sp-2);
}
.policy-card__text {
  font-size: var(--fs-small);
  color: var(--c-text-muted);
  line-height: var(--lh-normal);
}
.policy-card__text--placeholder {
  font-style: italic;
}

.policy-cta {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--sp-4);
  padding-block: var(--sp-8);
  border-top: 1px solid var(--c-border);
  padding-bottom: var(--sp-16);
}
.policy-cta__text {
  font-size: var(--fs-body-lg);
}
</style>
