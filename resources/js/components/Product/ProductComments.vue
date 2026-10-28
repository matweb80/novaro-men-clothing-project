<template>
  <section class="comment-section pg-section" aria-labelledby="commentsTitle">
    <div class="container">
      <header class="comment-section__head">
        <h3 id="commentsTitle" class="comment-section__title">نظرات کاربران</h3>
        <span v-if="commentCount" class="comment-section__count">
          {{ commentCount.toLocaleString("fa-IR") }} نظر
        </span>
      </header>

      <!-- کاربر واردنشده (productPageIsAuthenticated) -->
      <div v-if="!productPageIsAuthenticated" class="comment-login" role="note">
        <p class="comment-login__text">
          برای ثبت نظر، ابتدا وارد حساب کاربری خود شوید.
        </p>
        <a href="/login" class="comment-login__link">ورود به حساب</a>
      </div>

      <CommentList :comments="comments" :product="product" />

      <!-- ارسال نظر جدید -->
      <div v-if="productPageIsAuthenticated" class="comment-form">
        <h3 id="commentFormTitle" class="comment-form__title">
          نظر شما درباره این محصول
        </h3>
        <form class="comment-form__form" @submit.prevent="submitComment">
          <textarea
            id="productCommentText"
            v-model="newComment"
            rows="6"
            placeholder="تجربه‌ی خود را از این محصول بنویسید…"
            class="comment-form__field"
            aria-labelledby="commentFormTitle"
            aria-describedby="commentFormHint"
          ></textarea>
          <p id="commentFormHint" class="comment-form__hint">
            نظر شما پس از تأیید ادمین در سایت نمایش داده می‌شود.
          </p>
          <button
            type="submit"
            class="comment-form__submit btn btn--gold"
            :loading="isLoading"
            :disabled="!newComment.trim() || isLoading"
          >
            {{ isLoading ? "در حال ارسال…" : "ارسال نظر" }}
          </button>
        </form>

        <!-- اعلان برای صفحه‌خوان؛ دیالوگ مشترک تغییری نکرده است -->
        <p class="comment-live" role="status" aria-live="polite">
          {{ isVisibleDialog ? modal.message : "" }}
        </p>

        <base-dialog
          :show="isVisibleDialog"
          :title="modal.titleText"
          :header-color="dialogHeaderColor"
          @close="closeDialog"
        >
          <template #default>
            <base-spinner v-if="isLoading"></base-spinner>
            <h4 v-else class="comment-dialog__message">{{ modal.message }}</h4>
          </template>
          <template #actions>
            <base-button
              type="button"
              :class="modal.buttonColor"
              @click="closeDialog"
            >
              متوجه شدم
            </base-button>
          </template>
        </base-dialog>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from "vue";
import CommentList from "../partials/CommentList.vue";

import { useComments } from "@/composables/useComments.js";
import { useProductPageAppData } from "@/composables/useProductPageAppData";

const props = defineProps({
  comments: Array,
  product: Object,
});

const { productPageIsAuthenticated, productPageUser } = useProductPageAppData();

const { submitComment, newComment, isLoading, isVisibleDialog, modal } =
  useComments(props.product, productPageUser.value);

function closeDialog() {
  isVisibleDialog.value = false;
}

// رنگ هدر دیالوگ از توکن‌های NOVARO (همان‌هایی که BaseButton استفاده می‌کند)؛
// منطق useComments (modal.buttonColor = success | danger) بدون تغییر خوانده می‌شود.
const dialogHeaderColor = computed(() => {
  if (modal.value.buttonColor === "danger") return "var(--nv-error)";
  if (modal.value.buttonColor === "success") return "var(--nv-success)";
  return "var(--nv-primary)";
});

// تعداد نظرات واقعی دریافت‌شده (شامل پاسخ‌ها تا همان سقف عمق ۵ که CommentList نمایش می‌دهد)
const MAX_LEVEL = 5;
function countComments(list, level = 1) {
  if (!Array.isArray(list)) return 0;
  return list.reduce(
    (sum, c) =>
      sum + 1 + (level < MAX_LEVEL ? countComments(c.child, level + 1) : 0),
    0
  );
}
const commentCount = computed(() => countComments(props.comments));
</script>

<style scoped>
.comment-section {
  /* فقط توکن‌هایی که در BaseButton/BaseDialog دیده شد (--nv-*) با fallback پالت پروتوتایپ */
  --cs-ink: var(--nv-primary, #0f172a);
  --cs-paper: var(--nv-secondary, #ffffff);
  --cs-soft: var(--nv-surface-subtle, #f8fafc);
  --cs-gold: var(--nv-gold, #c8a96a);
  --cs-text: #1f2937;
  --cs-muted: #6b7280;
  --cs-line: #e4e8ee;
  --cs-line-strong: #cbd3df;
  --cs-radius: var(--nv-radius-sm, 2px);

  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 1px solid var(--cs-line);
  color: var(--cs-text);
}

.comment-section__head {
  display: flex;
  align-items: baseline;
  gap: 0.75rem;
  flex-wrap: wrap;
  margin-bottom: 1.5rem;
}
.comment-section__title {
  position: relative;
  margin: 0;
  padding-bottom: 0.75rem;
  font-size: 1.375rem;
  font-weight: 500;
  color: var(--cs-ink);
}
/* تنها تأکید طلایی عنوان: یک خط کوتاه */
.comment-section__title::after {
  content: "";
  position: absolute;
  inset-inline-start: 0;
  bottom: 0;
  width: 2.5rem;
  height: 2px;
  background: var(--cs-gold);
}
.comment-section__count {
  font-size: 0.875rem;
  color: var(--cs-muted);
}

/* پیام ورود */
.comment-login {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
  margin-bottom: 1.5rem;
  padding: 1rem 1.25rem;
  background: var(--cs-soft);
  border: 1px solid var(--cs-line);
  border-radius: var(--cs-radius);
}
.comment-login__text {
  margin: 0;
  font-size: 0.9375rem;
}
.comment-login__link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-block-size: 2.5rem;
  padding-inline: 1.25rem;
  background: var(--cs-gold);
  color: var(--cs-ink);
  border-radius: var(--cs-radius);
  font-size: 0.9375rem;
  font-weight: 500;
  text-decoration: none;
  transition: background-color 0.2s ease;
}
.comment-login__link:hover {
  background: var(--nv-gold-hover, #b38b47);
}
.comment-login__link:focus-visible {
  outline: 2px solid var(--cs-ink);
  outline-offset: 2px;
}

/* فرم ثبت نظر */
.comment-form {
  margin-top: 2.5rem;
  padding-top: 2rem;
  border-top: 1px solid var(--cs-line);
}
.comment-form__title {
  margin: 0 0 1rem;
  font-size: 1.125rem;
  font-weight: 500;
  color: var(--cs-ink);
}
.comment-form__form {
  display: flex;
  flex-direction: column;
}
.comment-form__field {
  width: 100%;
  min-height: 9rem;
  padding: 0.875rem 1rem;
  background: var(--cs-paper);
  color: var(--cs-text);
  border: 1px solid var(--cs-line-strong);
  border-radius: var(--cs-radius);
  font: inherit;
  font-size: 0.9375rem;
  line-height: 1.9;
  resize: vertical;
  transition: border-color 0.2s ease;
}
.comment-form__field::placeholder {
  color: var(--cs-muted);
}
.comment-form__field:focus {
  border-color: var(--cs-ink);
  outline: 2px solid var(--cs-ink);
  outline-offset: 1px;
}
.comment-form__hint {
  margin: 0.5rem 0 1.25rem;
  font-size: 0.8125rem;
  color: var(--cs-muted);
}
.comment-form__submit {
  align-self: flex-start;
  min-inline-size: 10rem;
}

.comment-live {
  position: absolute;
  inline-size: 1px;
  block-size: 1px;
  margin: -1px;
  padding: 0;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

.comment-dialog__message {
  margin: 0;
  line-height: 1.8;
  font-weight: 500;
}

@media (max-width: 575px) {
  .comment-section {
    margin-top: 2rem;
    padding-top: 1.5rem;
  }
  .comment-login {
    flex-direction: column;
    align-items: stretch;
    text-align: center;
  }
  .comment-form__submit {
    align-self: stretch;
    inline-size: 100%;
  }
}
</style>
