<script setup>
import { ref } from 'vue'

const email = ref('')
const statusMessage = ref('')

function onSubmit() {
  statusMessage.value = 'متشکریم! به‌زودی از تازه‌ترین‌های نوواررو باخبر می‌شوید.'
  email.value = ''
}
</script>

<template>
  <section class="newsletter" aria-labelledby="newsletterTitle">
    <div class="container newsletter__inner">
      <h2 id="newsletterTitle">جدیدترین‌ها را زودتر از همه ببینید</h2>
      <p class="newsletter__text">عضو خبرنامه نوواررو شوید و از کالکشن‌های جدید باخبر شوید.</p>
      <form class="newsletter__form" @submit.prevent="onSubmit">
        <label class="visually-hidden" for="newsletterEmail">ایمیل شما</label>
        <input
          id="newsletterEmail"
          v-model="email"
          type="email"
          class="newsletter__input"
          placeholder="ایمیل شما"
          required
          autocomplete="email"
        />
        <button type="submit" class="btn btn--primary">عضویت</button>
      </form>
      <p
        class="newsletter__success"
        :class="{ 'visually-hidden': !statusMessage }"
        aria-live="polite"
      >
        {{ statusMessage }}
      </p>
    </div>
  </section>
</template>
