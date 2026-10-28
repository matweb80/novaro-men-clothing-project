<script setup>
import ImagePlate from '@/components/ui/ImagePlate.vue'
</script>

<template>
  <section class="editorial" aria-labelledby="editorialTitle">
    <ImagePlate
      class="editorial__media"
      src="images/homepage/editorial-campaign-01.webp"
      alt="پشت صحنه عکاسی ادیتوریال نوواررو، مدل با ژاکت چهارخانه روی صحنه استودیو"
    />
    <div class="editorial__panel container">
      <p class="eyebrow">مجله نوواررو</p>
      <h2 id="editorialTitle">سبک، بدون تلاش اضافه.</h2>
      <p class="editorial__text">
        ما به قطعاتی ساده و بادوام باور داریم؛ لباس‌هایی که با گذر زمان معنا پیدا می‌کنند، نه اینکه با هر فصل تغییر
        کنند.
      </p>
      <a href="#" class="editorial__link">مطالعه بیشتر <span aria-hidden="true">←</span></a>
    </div>
  </section>
</template>
