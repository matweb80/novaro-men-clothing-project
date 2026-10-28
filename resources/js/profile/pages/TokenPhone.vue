<template>
  <form @submit.prevent="submitToken(token)" v-if="onMount">
    <div class="form-group">
      <label for="token" class="form-label"> {{ $t("Token") }} </label>
      <keep-alive>
        <input
          type="text"
          name="token"
          class="form-input"
          placeholder="توکن ارسال شده را وارد کنید"
          v-model="token"
        />
      </keep-alive>
      <div class="token-spinner" v-if="isLoading">
        <base-spinner></base-spinner>
        <span>در حال بررسی</span>
      </div>
      <p v-if="message && !isLoading" class="form-error">
        {{ message }}
      </p>
    </div>
    <div class="form-group">
      <base-button class="btn btn--primary" :disabled="isLoading">
        {{
          isLoading ? "در حال اعتبار سنجی…" : "اعتبار سنجی توکن"
        }}</base-button
      >
    </div>

    <base-dialog
      class="base-dialog"
      :show="isVisibleDialog"
      :title="modal.titleText"
      :header-color="modal.headerColor"
      fixed
    >
      <template #default>
        <h4>{{ modal.message }}</h4>
      </template>
    </base-dialog>
  </form>
</template>

<script>
import { onMounted, ref } from "vue";
import { useToken } from "../composables/useToken";
import { useOrders } from "../composables/useOrders";

export default {
  setup() {
    const onMount = ref(false);
    const { isVisibleDialog, modal, isLoading, message, submitToken } =
      useToken();

    const { userPhone, fetchOrders } = useOrders();

    onMounted(() => {
      fetchOrders(1, false);
      if (!!userPhone) {
        onMount.value = true;
      }
    });

    return {
      onMount,
      isVisibleDialog,
      modal,
      isLoading,
      message,
      submitToken,
      userPhone,
    };
  },
};
</script>

<style scoped>
.base-spinner {
  transform: scale(0.65);
  justify-content: flex-start !important;
}

.submit-button {
  font-weight: 600;
  padding: 6px 20px;
  border-radius: 10px !important;
}

.token-spinner {
  display: flex;
  align-items: center;
}
.token-spinner div {
  width: min-content;
}
.token-spinner div.spinner {
  transform: scale(0.3);
  height: 35px;
  width: min-content;
  margin: 0 -20px;
}
.token-spinner span {
  color: var(--primary-color);
}

h4 {
  line-height: 1.8;
}
</style>