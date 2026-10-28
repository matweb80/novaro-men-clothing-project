<script setup>
import Breadcrumb from "@/components/layout/Breadcrumb.vue";
import FaqView from "@/views/FaqView.vue";

const VALUES = [
  {
    title: "ساخت دقیق",
    text: "هر قطعه با توجه به جزئیات دوخت و برش تولید می‌شود؛ نه برای یک فصل، برای سال‌ها.",
  },
  {
    title: "طراحی ماندگار",
    text: "به‌جای دنبال‌کردن ترندهای زودگذر، به خطوط ساده و کلاسیکی باور داریم که با زمان کهنه نمی‌شوند.",
  },
  {
    title: "متریال آگاهانه",
    text: "انتخاب پارچه و متریال با در نظر گرفتن دوام، احساس پوشش و کیفیت واقعی انجام می‌شود.",
  },
  {
    title: "دقت در جزئیات",
    text: "از انتخاب دکمه تا نحوه‌ی دوخت آستین، هیچ جزئیاتی تصادفی نیست.",
  },
];
</script>

<template>
  <main id="main">
    <div class="page-hero container">
      <Breadcrumb current-label="درباره ما" />
      <p class="eyebrow">نوواررو</p>
      <h1 class="page-hero__title">سبک، بدون تلاش اضافه.</h1>
      <p class="page-hero__lead">
        ما به قطعاتی ساده و بادوام باور داریم؛ لباس‌هایی که با گذر زمان معنا
        پیدا می‌کنند، نه اینکه با هر فصل تغییر کنند.
      </p>
    </div>

    <section class="editorial" aria-labelledby="aboutStoryTitle">
      <div class="editorial__media img-plate">
        <div class="img-plate__inner">
          <!-- TODO: تصویر واقعی آتلیه/تیم نوواررو جایگزین شود -->
          <img
            class="img-plate__photo"
            src="/public/images/about-atelier.webp"
            alt="نمایی از فرآیند طراحی و دوخت نوواررو"
            loading="lazy"
          />
        </div>
      </div>
      <div class="editorial__panel container">
        <p class="eyebrow">فلسفه ما</p>
        <h2 id="aboutStoryTitle">چرا نوواررو؟</h2>
        <p class="editorial__text">
          نوواررو از این ایده شکل گرفت که پوشاک مردانه نباید بین «ساده» و
          «باکیفیت» یکی را انتخاب کند. ما هر مجموعه را با نگاهی مینیمال و دقیق
          طراحی می‌کنیم؛ قطعاتی که در کمد لباس شما ماندگار می‌شوند، نه اینکه یک
          فصل بعد کنار گذاشته شوند.
        </p>
      </div>
    </section>

    <!-- ارزش‌های برند -->
    <section class="values container" aria-labelledby="valuesTitle">
      <div class="section-head">
        <p class="eyebrow">اصول ما</p>
        <h2 id="valuesTitle">چه چیزی نوواررو را متفاوت می‌کند</h2>
      </div>
      <div class="values__grid">
        <article v-for="value in VALUES" :key="value.title" class="value-card">
          <h3 class="value-card__title">{{ value.title }}</h3>
          <p class="value-card__text">{{ value.text }}</p>
        </article>
      </div>
    </section>

    <!-- FAQ Questions -->
    <FaqView />
    <!-- بیانیه برند — بازاستفاده از الگوی .brand-statement موجود -->
    <section class="brand-statement" aria-labelledby="aboutStatementTitle">
      <div class="container brand-statement__inner">
        <p id="aboutStatementTitle" class="brand-statement__word latin">
          NOVARO
        </p>
        <p class="brand-statement__tag latin">DEFINED BY STYLE</p>
        <p class="brand-statement__text">
          ما به سادگی، دوام و طراحی درست باور داریم. هر قطعه نوواررو برای ماندن
          ساخته می‌شود، نه برای یک فصل.
        </p>
      </div>
    </section>
  </main>
</template>

<style scoped>
/* --- Values grid: تنها بخش واقعاً جدید این صفحه --- */
.values {
  padding-block: var(--sp-16);
}
.values__grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--sp-5);
  margin-top: var(--sp-8);
}
@media (min-width: 576px) {
  .values__grid {
    grid-template-columns: 1fr 1fr;
  }
}
@media (min-width: 992px) {
  .values__grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
.value-card {
  border: 1px solid var(--c-border);
  border-radius: var(--r-md);
  padding: var(--sp-6) var(--sp-5);
  background: var(--c-white);
}
.value-card__title {
  font-size: var(--fs-h4);
  font-weight: 600;
  margin-bottom: var(--sp-2);
}
.value-card__text {
  font-size: var(--fs-small);
  color: var(--c-text-muted);
  line-height: var(--lh-normal);
}
</style>
