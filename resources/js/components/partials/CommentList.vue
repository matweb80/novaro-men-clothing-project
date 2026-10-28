<template>
  <div v-if="comments && comments.length" class="comment-list">
    <article v-for="comment in comments" :key="comment.id" class="comment">
      <header class="comment__head">
        <div class="comment__meta">
          <strong class="comment__author">{{ comment.user?.name }}</strong>
          <time
            v-if="comment.created_at"
            class="comment__date"
            :datetime="comment.created_at"
            >{{ formatDate(comment.created_at) }}</time
          >
        </div>

        <button
          v-if="productPageIsAuthenticated"
          type="button"
          class="comment__reply"
          @click="openReply(comment.id)"
        >
          <font-awesome-icon
            icon="fa-solid fa-reply"
            class="comment__reply-icon"
            aria-hidden="true"
          />
          <span>پاسخ</span>
        </button>
      </header>

      <p class="comment__body">{{ comment.comment }}</p>

      <!-- پاسخ‌ها -->
      <div v-if="level < 5 && comment.child?.length" class="comment__replies">
        <CommentList
          :comments="comment.child"
          :product="product"
          :level="level + 1"
        />
      </div>

      <!-- reply modal (منطق و bindingها بدون تغییر) -->
      <base-dialog
        :show="!!replyingTo"
        :title="'ارسال پاسخ به ' + comment.user?.name"
        header-color="var(--nv-primary)"
        @close="replyingTo = null"
        @submit-dialog="submitReply"
        :fixed="isLoading"
      >
        <base-spinner v-if="isLoading"></base-spinner>
        <h4 v-else>{{ message }}</h4>
        <textarea
          v-if="!isLoading"
          v-model="replyText"
          rows="4"
          placeholder="پاسخ خود را بنویسید..."
          aria-label="متن پاسخ"
          class="reply-textarea"
        ></textarea>
      </base-dialog>
    </article>
  </div>

  <!-- No Comments -->
  <div v-else class="comment-empty">
    <p class="comment-empty__title">هنوز نظری ثبت نشده است</p>
    <p class="comment-empty__text">
      نخستین تجربه از این محصول را شما با دیگران به اشتراک بگذارید.
    </p>
  </div>
</template>

<script setup>
import CommentList from "./CommentList.vue";
import { useProductPageAppData } from "@/composables/useProductPageAppData.js";
import { useReplyComments } from "@/composables/useReplyComments.js";

const props= defineProps({
  comments: Array,
  product: Object,
  level: {
    type: Number,
    default: 1,
  },
});


const { productPageIsAuthenticated, productPageUser } = useProductPageAppData();

const { replyText, replyingTo, isLoading, message, openReply, submitReply } =
  useReplyComments(productPageUser.value);

function formatDate(dateStr) {
  if (!dateStr) return "";
  return new Date(dateStr).toLocaleDateString("fa-IR");
}
</script>

<style scoped>
.comment-list,
.comment-empty {
  --cs-ink: var(--nv-primary, #0f172a);
  --cs-paper: var(--nv-secondary, #ffffff);
  --cs-soft: var(--nv-surface-subtle, #f8fafc);
  --cs-text: #1f2937;
  --cs-muted: #6b7280;
  --cs-line: #e4e8ee;
  --cs-line-strong: #cbd3df;
  --cs-radius: var(--r-sm, 2px);
}

/* هر نظر: بدون کارت و سایه، فقط خط جداکننده ظریف (حال‌وهوای Editorial) */
.comment {
  padding-block: 1.25rem;
  border-bottom: 1px solid var(--cs-line);
}
.comment-list > .comment:last-child {
  border-bottom: none;
}

.comment__head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  margin-bottom: 0.5rem;
}
.comment__meta {
  display: flex;
  align-items: baseline;
  flex-wrap: wrap;
  gap: 0.25rem 0.75rem;
  min-width: 0;
}
.comment__author {
  font-size: 0.9375rem;
  font-weight: 600;
  color: var(--cs-ink);
}
.comment__date {
  font-size: 0.8125rem;
  color: var(--cs-muted);
}

.comment__body {
  margin: 0;
  color: var(--cs-text);
  font-size: 0.9375rem;
  line-height: 2;
  white-space: pre-line;
  overflow-wrap: anywhere;
}

.comment__reply {
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
  min-block-size: 2.25rem;
  padding-inline: 0.5rem;
  background: transparent;
  border: none;
  border-radius: var(--cs-radius);
  color: var(--cs-ink);
  font: inherit;
  font-size: 0.8125rem;
  cursor: pointer;
  transition: background-color 0.2s ease;
}
.comment__reply:hover {
  background: var(--cs-soft);
}
.comment__reply:focus-visible {
  outline: 2px solid var(--cs-ink);
  outline-offset: 2px;
}
.comment__reply-icon {
  font-size: 0.75rem;
}

/* پاسخ‌های تودرتو */
.comment__replies {
  margin-top: 0.75rem;
  padding-inline-start: 1rem;
  border-inline-start: 2px solid var(--cs-line);
}
.comment__replies .comment {
  padding-block: 0.75rem;
}

/* Empty State */
.comment-empty {
  padding: 2rem 1rem;
  text-align: center;
  background: var(--cs-soft);
  border: 1px dashed var(--cs-line-strong);
  border-radius: var(--cs-radius);
}
.comment-empty__title {
  margin: 0 0 0.25rem;
  font-size: 1rem;
  font-weight: 500;
  color: var(--cs-ink);
}
.comment-empty__text {
  margin: 0;
  font-size: 0.875rem;
  color: var(--cs-muted);
}

/* داخل دیالوگ پاسخ */
.reply-textarea {
  box-sizing: border-box;
  width: 100%;
  margin-top: 0.5rem;
  padding: 0.75rem 0.875rem;
  border: 1px solid var(--cs-line-strong);
  border-radius: var(--cs-radius);
  font: inherit;
  font-size: 0.9375rem;
  line-height: 1.9;
  resize: vertical;
}
.reply-textarea:focus {
  border-color: var(--cs-ink);
  outline: 2px solid var(--cs-ink);
  outline-offset: 1px;
}

h4 {
  line-height: 1.8;
}
</style>
