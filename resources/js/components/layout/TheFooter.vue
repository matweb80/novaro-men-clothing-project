<script setup>
import { CATEGORY_TREE } from "@/data/categories";
import { BRAND_MARK } from "@/assets/images/brand-mark";
</script>

<template>
  <footer class="site-footer">
    <div class="container site-footer__grid">
      <div class="site-footer__brand">
        <img class="site-footer__mark" :src="BRAND_MARK" alt="" />
        <p class="site-footer__word latin">NOVARO</p>
        <p class="site-footer__tag latin">DEFINED BY STYLE</p>
      </div>

      <nav class="site-footer__col" aria-label="دسته‌بندی‌ها">
        <p class="site-footer__heading">دسته‌بندی‌ها</p>
        <ul>
          <li v-for="cat in CATEGORY_TREE" :key="cat.id">
            <a href="#">{{ cat.name }}</a>
          </li>
        </ul>
      </nav>

      <nav class="site-footer__col" aria-label="خدمات مشتریان">
        <p class="site-footer__heading">مشتریان</p>
        <ul>
          <li><a href="#">راهنمای خرید</a></li>
          <li><a href="#">راهنمای سایز</a></li>
          <li><a href="/shipping-returns">بازگشت و مرجوعی</a></li>
          <li><a href="/contact-us">تماس با ما</a></li>
        </ul>
      </nav>

      <nav class="site-footer__col" aria-label="درباره نوواررو">
        <p class="site-footer__heading">نوواررو</p>
        <ul>
          <li><a href="/about-us">درباره ما</a></li>
          <li><a href="/about-us/#faq">سوالات متداول</a></li>
          <li><a href="/blog">مجله</a></li>
          <li><a href="/privacy-policy">حریم خصوصی</a></li>
          <li><a href="/terms">قوانین</a></li>
        </ul>
      </nav>
    </div>

    <div class="container site-footer__bottom">
      <p class="site-footer__legal">
        © ۱۴۰۴ نوواررو — تمامی حقوق محفوظ است | طراحی و پشتیبانی :
        <a href="https://www.mahdihp.com/">مت وب</a>
      </p>
      <div class="site-footer__social">
        <base-social-icons></base-social-icons>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.site-footer__heading {
  border-bottom: 1px solid var(--c-navy-40);
  display: inline;
  padding-bottom: 2px;
}
</style>