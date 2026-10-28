<script setup>
import ImagePlate from '@/components/ui/ImagePlate.vue'
</script>

<template>
  <section class="hero" aria-label="نوواررو">
    <ImagePlate
      class="hero__media"
      src="images/homepage/hero-men-fashion.webp"
      alt="مرد پوشیده در بلیزر مشکی، ژست ادیتوریال با نورپردازی نمایشی"
      loading="eager"
    />
    <div class="hero__content">
      <h1 class="hero__word latin">NOVARO</h1>
      <p class="hero__tag latin">DEFINED BY STYLE</p>
      <p class="hero__line">پوشاکی برای مردانی که سبک را انتخاب می‌کنند، نه دنبال می‌کنند.</p>
      <a href="#new-arrivals" class="btn btn--primary">مشاهده کالکشن جدید</a>
    </div>
  </section>
</template>
