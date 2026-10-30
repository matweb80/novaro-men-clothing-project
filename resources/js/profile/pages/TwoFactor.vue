<template>
  <form @submit.prevent="submitTwoFactor(userPhone, userTwoFactorType)" class="profile-form">
    <div class="form-group">
      <label for="type" class="form-label"> {{ $t("Type") }} </label>
      <!-- 🌀 Loader -->
      <div v-if="isLoading">
        <base-spinner class="base-spinner"></base-spinner>
      </div>
      <keep-alive v-else>
        <select
          name="type"
          id="type"
          class="form-input"
          v-model="userTwoFactorType"
        >
          <option
            v-for="type in types"
            :key="type.type"
            :value="type.name"
            :selected="userTwoFactorType == type.name"
          >
            {{ type.name }}
          </option>
        </select>
      </keep-alive>
    </div>
    <div class="form-group">
      <label for="phone" class="form-label"> {{ $t("Phone Number") }} </label>
      <keep-alive>
        <input
          type="tel"
          name="phone"
          id="phone"
          class="form-input phone-input"
          :class="{ 'has-error': error.phone }"
          placeholder="شماره تلفن همراه خود را وارد کنید"
          v-model="userPhone"
        />
      </keep-alive>
      <p v-if="error.phone" class="form-error">
        {{ error.phone }}
      </p>
    </div>
    <div class="form-group">
      <button class="btn btn--primary" :disabled="twoFactorLoading">{{
        twoFactorLoading ? "در حال ذخیره…" : "ذخیره تغییرات"
      }}</button>
    </div>
    <div class="two-factor-spinner" v-if="twoFactorLoading">
      <base-spinner></base-spinner>
      <span>در حال بررسی</span>
    </div>
    <p v-if="message && !twoFactorLoading" class="alert alert-warning">
      {{ message }}
    </p>
  </form>
</template>

<script>
import { onMounted, ref, watch } from "vue";
import { useOrders } from "../composables/useOrders";
import { useTwoFactor } from "../composables/useTwoFactor";
import { useToken } from "../composables/useToken";
import BaseSpinner from "../../components/UI/BaseSpinner.vue";

export default {
  components: { BaseSpinner },
  setup() {
    const types = ref([
      {
        name: "off",
        type: "Disable",
      },
      {
        name: "sms",
        type: "SMS",
      },
    ]);

    const { userPhone, userTwoFactorType, fetchOrders, isLoading } =
      useOrders();

    const { error, message, twoFactorLoading, submitTwoFactor, validateForm } =
      useTwoFactor();

    const { isVisibleDialog, modal, submitToken } = useToken();

    onMounted(async () => {
      fetchOrders(1, false);
      validateForm(userPhone.value);
    });

    watch(
      () => userPhone.value,
      () => {
        delete error.value.phone;
      }
    );
    watch(
      () => userPhone.value,
      () => {
        message.value = null;
      }
    );

    return {
      types,
      userPhone,
      userTwoFactorType,
      isLoading,
      error,
      message,
      twoFactorLoading,
      submitTwoFactor,
      isVisibleDialog,
      modal,
      submitToken,
    };
  },
};
</script>

<style scoped>
.base-spinner {
  transform: scale(0.65);
  justify-content: flex-start !important;
}

.form-control {
  appearance: auto;
}

.error-text {
  color: #ef4444;
  font-size: 12px;
  margin: 4px 0;
}

.phone-input {
  direction: rtl;
  text-align: right;
}

input.has-error {
  border-color: #ef4444;
}

.submit-button {
  font-weight: 600;
  padding: 6px 20px;
  border-radius: 10px !important;
}

.two-factor-spinner {
  display: flex;
  align-items: center;
}
.two-factor-spinner div {
  width: min-content;
}
.two-factor-spinner div.spinner {
  transform: scale(0.3);
  height: 35px;
  width: min-content;
  margin: 0 -20px;
}
.two-factor-spinner span {
  color: var(--primary-color);
}
</style>