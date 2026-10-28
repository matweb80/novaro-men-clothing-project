<script setup>
import { onMounted, ref, watch } from "vue";
import { formatToman } from "@/utils/formatPrice";
import { useDiscount } from "@/composables/useDiscount";
import { useCart } from "@/composables/useCart";

const discount = ref(null);
const cartName = ref("my-cart");

const {
  cartDiscount,
  error,
  checkDiscount,
  deleteDiscount,
  isVisibleDialog,
  modal,
  isLoading,
} = useDiscount();

const { cart, loadCart } = useCart();

onMounted(() => {
  if (cart.totalDiscountPrice) {
    cartDiscount.value = cart.discount;
  }
});

watch(
  () => cartDiscount.value,
  () => {
    loadCart();
  }
);
watch(
  () => discount.value,
  () => {
    error.value = null;
  }
);
</script>

<template>
  <transition name="discount">
    <div class="discount-code">
      <!-- حالت اعمال‌شده -->
      <form
        v-if="cart.totalDiscountPrice"
        @submit.prevent="deleteDiscount(cartName)"
        class="discount-applied"
      >
        <div>
          <p class="discount-applied__code latin" dir="ltr">
            {{ cartDiscount?.code }}
          </p>
          <p v-if="cartDiscount?.percent" class="discount-applied__amount">
            <span class="latin" dir="ltr">{{ cartDiscount?.percent }}</span
            > درصد تخفیف اعمال شد
          </p>
          <p v-else class="discount-applied__amount">کد تخفیف اعمال شد</p>
        </div>
        <button
          type="submit"
          class="link-btn link-btn--danger"
          :disabled="isLoading"
        >
          حذف
        </button>
      </form>

      <!-- حالت ورود کد -->
      <form
        v-else
        class="discount-form"
        novalidate
        @submit.prevent="checkDiscount(discount, cartName)"
      >
        <label class="form-label" for="discountCodeInput">کد تخفیف</label>
        <div class="discount-form__row">
          <input
            v-model="discount"
            type="text"
            name="discount"
            class="form-input latin"
            dir="ltr"
            placeholder="کد تخفیف را وارد کنید"
            :aria-invalid="!!error"
            aria-describedby="discountCodeError"
            :disabled="isLoading"
          />
          <button
            type="submit"
            class="btn btn--outline btn--sm"
            :disabled="isLoading"
          >
            {{ isLoading ? "در حال بررسی…" : "اعمال" }}
          </button>
        </div>
        <p v-if="error" class="form-error" role="alert">
          {{ error }}
        </p>
      </form>
    </div>
  </transition>
  <!-- mini dialog -->
  <base-mini-dialog
    :show="isVisibleDialog"
    :message="modal.message"
    :type="modal.type"
    @close="isVisibleDialog = false"
  ></base-mini-dialog>
</template>

<style scoped>
.discount-code {
  padding-block: var(--sp-4);
  border-top: 1px solid var(--c-border);
  border-bottom: 1px solid var(--c-border);
  margin-block: var(--sp-4);
}
.discount-code--compact {
  padding-block: var(--sp-3);
  margin-block: var(--sp-3);
}

.discount-form__row {
  display: flex;
  gap: var(--sp-2);
  margin-top: var(--sp-2);
}
.discount-form__row .form-input {
  flex: 1;
}

.form-label {
  font-size: var(--fs-small);
  font-weight: 600;
  color: var(--c-text);
}
.form-input {
  padding: var(--sp-2) var(--sp-3);
  border: 1px solid var(--c-border-strong);
  border-radius: var(--r-sm);
  background: var(--c-white);
  color: var(--c-text);
  font-size: var(--fs-small);
  font-family: var(--font);
}
.form-input:focus {
  border-color: var(--c-navy);
}
.form-input[aria-invalid="true"] {
  border-color: var(--c-danger);
}
.form-error {
  font-size: var(--fs-caption);
  color: var(--c-danger);
  margin-top: var(--sp-2);
}

.discount-applied {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: var(--sp-3);
}
.discount-applied__code {
  font-weight: 600;
}
.discount-applied__amount {
  font-size: var(--fs-small);
  color: var(--c-success);
  margin-top: 2px;
}

.link-btn {
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
  font-size: var(--fs-small);
  font-family: var(--font);
  color: var(--c-danger);
  text-decoration: underline;
}
.link-btn:disabled {
  opacity: 0.5;
  cursor: default;
}
</style>
