<script setup>
import { reactive, ref } from "vue";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";
import { CONTACT_INFO } from "@/data/contact.js";
import { http } from "@/utils/http";

const form = reactive({
  name: "",
  email: "",
  phone: "",
  subject: "",
  message: "",
});

const errors = reactive({
  name: "",
  email: "",
  phone: "",
  subject: "",
  message: "",
});

const submitting = ref(false);
const submitted = ref(false);
const submitFailed = ref(false);

const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const PHONE_RE = /^0\d{10}$/;

function validate() {
  errors.name = form.name.trim().length < 2 ? "نام خود را کامل وارد کنید." : "";
  errors.email = !EMAIL_RE.test(form.email.trim())
    ? "یک ایمیل معتبر وارد کنید."
    : "";
  errors.phone =
    form.phone.trim() && !PHONE_RE.test(form.phone.trim())
      ? "شماره تماس را به‌صورت ۰۹۱۲۳۴۵۶۷۸۹ وارد کنید."
      : "";
  errors.subject =
    form.subject.trim().length < 3 ? "موضوع پیام را وارد کنید." : "";
  errors.message =
    form.message.trim().length < 10 ? "متن پیام باید حداقل ۱۰ نویسه باشد." : "";

  return !Object.values(errors).some(Boolean);
}

async function handleSubmit() {
  submitFailed.value = false;
  submitted.value = false;
  if (!validate()) return;

  submitting.value = true;
  try {
    const res = await http("/api/contact-us", {
      method: "POST",
      body: JSON.stringify(form),
    });

    const data = await res.json();

    if (!res.ok) throw data;

    if (data.success) {
      submitted.value = true;
      form.name = "";
      form.email = "";
      form.phone = "";
      form.subject = "";
      form.message = "";
    }
  } catch (e) {
    submitFailed.value = true;
  } finally {
    submitting.value = false;
  }
}
</script>

<template>
  <main id="main">
    <div class="page-hero container">
      <Breadcrumb current-label="تماس با ما" />
      <p class="eyebrow">در ارتباط باشید</p>
      <h1 class="page-hero__title">تماس با ما</h1>
      <p class="page-hero__lead">
        سوالی درباره‌ی سفارش، محصولات یا همکاری دارید؟ فرم زیر را پر کنید تا تیم
        نوواررو در اسرع وقت با شما تماس بگیرد.
      </p>
    </div>

    <section class="contact container" aria-labelledby="contactFormTitle">
      <div class="contact__grid">
        <!-- فرم تماس -->
        <form class="contact-form" novalidate @submit.prevent="handleSubmit">
          <h2 id="contactFormTitle" class="contact-form__title">
            پیام خود را برای ما بفرستید
          </h2>

          <div class="form-group">
            <label class="form-label" for="cfName">نام و نام خانوادگی</label>
            <input
              id="cfName"
              v-model="form.name"
              type="text"
              class="form-input"
              :aria-invalid="!!errors.name"
              aria-describedby="cfNameError"
              autocomplete="name"
            />
            <p v-if="errors.name" id="cfNameError" class="form-error">
              {{ errors.name }}
            </p>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="cfEmail">ایمیل</label>
              <input
                id="cfEmail"
                v-model="form.email"
                type="email"
                class="form-input"
                :aria-invalid="!!errors.email"
                aria-describedby="cfEmailError"
                autocomplete="email"
              />
              <p v-if="errors.email" id="cfEmailError" class="form-error">
                {{ errors.email }}
              </p>
            </div>

            <div class="form-group">
              <label class="form-label" for="cfPhone"
                >شماره تماس <span class="form-optional">(اختیاری)</span></label
              >
              <input
                id="cfPhone"
                v-model="form.phone"
                type="tel"
                class="form-input latin"
                dir="ltr"
                :aria-invalid="!!errors.phone"
                aria-describedby="cfPhoneError"
                autocomplete="tel"
              />
              <p v-if="errors.phone" id="cfPhoneError" class="form-error">
                {{ errors.phone }}
              </p>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="cfSubject">موضوع</label>
            <input
              id="cfSubject"
              v-model="form.subject"
              type="text"
              class="form-input"
              :aria-invalid="!!errors.subject"
              aria-describedby="cfSubjectError"
            />
            <p v-if="errors.subject" id="cfSubjectError" class="form-error">
              {{ errors.subject }}
            </p>
          </div>

          <div class="form-group">
            <label class="form-label" for="cfMessage">متن پیام</label>
            <textarea
              id="cfMessage"
              v-model="form.message"
              class="form-input form-textarea"
              rows="5"
              :aria-invalid="!!errors.message"
              aria-describedby="cfMessageError"
            ></textarea>
            <p v-if="errors.message" id="cfMessageError" class="form-error">
              {{ errors.message }}
            </p>
          </div>

          <button
            type="submit"
            class="btn btn--primary btn--block"
            :disabled="submitting"
          >
            {{ submitting ? "در حال ارسال…" : "ارسال پیام" }}
          </button>

          <p v-if="submitted" class="form-success" aria-live="polite">
            پیام شما با موفقیت ارسال شد. تیم نوواررو در اسرع وقت با شما تماس
            می‌گیرد.
          </p>
          <p v-if="submitFailed" class="form-fail" aria-live="polite">
            ارسال پیام با خطا مواجه شد. لطفاً دوباره تلاش کنید.
          </p>
        </form>

        <!-- اطلاعات تماس -->
        <aside class="contact-info" aria-label="راه‌های ارتباطی">
          <div class="contact-info__item">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path
                d="M5 4h3l2 5-2.5 1.5a11 11 0 0 0 5 5L14 13l5 2v3a2 2 0 0 1-2 2C10.5 20 4 13.5 4 6a2 2 0 0 1 1-2Z"
              />
            </svg>
            <div>
              <p class="contact-info__label">تلفن تماس</p>
              <a
                :href="`tel:` + CONTACT_INFO.phoneLink"
                class="contact-info__value latin"
                dir="ltr"
              >
                {{ CONTACT_INFO.phone || "به‌زودی تکمیل می‌شود" }}
              </a>
            </div>
          </div>

          <div class="contact-info__item">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <rect x="3" y="5" width="18" height="14" rx="2" />
              <path d="m3 7 9 6 9-6" />
            </svg>
            <div>
              <p class="contact-info__label">ایمیل پشتیبانی</p>
              <a
                :href="`mailto:` + CONTACT_INFO.email"
                class="contact-info__value latin"
                dir="ltr"
              >
                {{ CONTACT_INFO.email || "به‌زودی تکمیل می‌شود" }}
              </a>
            </div>
          </div>

          <div class="contact-info__item">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M12 21s-7-5.2-7-11a7 7 0 0 1 14 0c0 5.8-7 11-7 11Z" />
              <circle cx="12" cy="10" r="2.5" />
            </svg>
            <div>
              <p class="contact-info__label">آدرس</p>
              <p class="contact-info__value">
                {{ CONTACT_INFO.address || "به‌زودی تکمیل می‌شود" }}
              </p>
            </div>
          </div>

          <div class="contact-info__item">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <circle cx="12" cy="12" r="9" />
              <path d="M12 7v5l3.5 2" />
            </svg>
            <div>
              <p class="contact-info__label">ساعات پاسخگویی</p>
              <p class="contact-info__value">
                {{ CONTACT_INFO.hours || "به‌زودی تکمیل می‌شود" }}
              </p>
            </div>
          </div>
        </aside>
      </div>
    </section>
  </main>
</template>

<style scoped>
/* ==========================================================================
   CONTACT PAGE — از توکن‌های design-tokens.css موجود استفاده می‌کند
   ========================================================================== */
.contact {
  padding-bottom: var(--sp-20);
}
.contact__grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--sp-12);
}
@media (min-width: 992px) {
  .contact__grid {
    grid-template-columns: 1.4fr 1fr;
    align-items: start;
  }
}

/* --- فرم --- */
.contact-form {
  display: flex;
  flex-direction: column;
  gap: var(--sp-5);
  background: var(--c-white);
  border: 1px solid var(--c-border);
  border-radius: var(--r-md);
  padding: var(--sp-6);
}
@media (min-width: 768px) {
  .contact-form {
    padding: var(--sp-8);
  }
}
.contact-form__title {
  font-size: var(--fs-h3);
  font-weight: 600;
  margin-bottom: var(--sp-2);
}

.form-row {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--sp-5);
}
@media (min-width: 576px) {
  .form-row {
    grid-template-columns: 1fr 1fr;
  }
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: var(--sp-2);
}
.form-label {
  font-size: var(--fs-small);
  font-weight: 600;
  color: var(--c-text);
}
.form-optional {
  font-weight: 400;
  color: var(--c-text-muted);
}

.form-input {
  width: 100%;
  padding: var(--sp-3) var(--sp-4);
  border: 1px solid var(--c-border-strong);
  border-radius: var(--r-sm);
  background: var(--c-white);
  color: var(--c-text);
  font-size: var(--fs-body);
  font-family: var(--font);
  transition: border-color var(--dur-fast) var(--ease);
}
.form-input::placeholder {
  color: var(--c-text-muted);
}
.form-input:focus {
  border-color: var(--c-navy);
}
.form-input[aria-invalid="true"] {
  border-color: var(--c-danger);
}
.form-textarea {
  resize: vertical;
  min-height: 120px;
}

.form-error {
  font-size: var(--fs-small);
  color: var(--c-danger);
}
.form-success {
  font-size: var(--fs-small);
  color: var(--c-success);
}
.form-fail {
  font-size: var(--fs-small);
  color: var(--c-danger);
}

/* --- ستون اطلاعات تماس --- */
.contact-info {
  display: flex;
  flex-direction: column;
  gap: var(--sp-6);
  background: var(--c-bg);
  border: 1px solid var(--c-border);
  border-radius: var(--r-md);
  padding: var(--sp-6);
}
@media (max-width: 992px) {
  .contact-info {
    flex-direction: row;
    justify-content: center;
    flex-wrap: wrap;
  }
  .contact-info div{
    flex-grow: 1;
  }
}
.contact-info__item {
  display: flex;
  align-items: flex-start;
  gap: var(--sp-3);
}
.contact-info__item svg {
  flex-shrink: 0;
  width: 22px;
  height: 22px;
  stroke: var(--c-navy);
  fill: none;
  stroke-width: 1.3;
  margin-top: 2px;
}
.contact-info__label {
  font-size: var(--fs-caption);
  letter-spacing: var(--ls-wide);
  text-transform: uppercase;
  color: var(--c-text-muted);
  margin-bottom: 2px;
}
.contact-info__value {
  font-size: var(--fs-body);
  color: var(--c-text);
}
</style>
