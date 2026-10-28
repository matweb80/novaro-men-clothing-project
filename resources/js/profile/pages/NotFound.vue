<template>
  <section class="not-found container">
    <p class="not-found__code latin" dir="ltr">404</p>
    <h1 class="not-found__title">صفحه مورد نظر پیدا نشد</h1>
    <p class="not-found__text">
      ممکن است آدرس را اشتباه وارد کرده باشید یا این صفحه جابه‌جا شده باشد.
    </p>
    <div class="not-found__actions">
      <a href="/" class="btn btn--primary"
        >بازگشت به صفحه اصلی</a
      >
      <a href="/contact-us" class="btn btn--outline"
        >تماس با ما</a
      >
    </div>
  </section>
</template>

<style scoped>
.not-found {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: var(--sp-3);
  padding-block: var(--sp-20);
}
.not-found__code {
  font-size: 72px;
  font-weight: 600;
  color: var(--c-navy);
  line-height: 1;
}
.not-found__title {
  font-size: var(--fs-h3);
  font-weight: 600;
}
.not-found__text {
  color: var(--c-text-muted);
  max-width: 420px;
}
.not-found__actions {
  display: flex;
  gap: var(--sp-3);
  margin-top: var(--sp-4);
  flex-wrap: wrap;
  justify-content: center;
}
</style>