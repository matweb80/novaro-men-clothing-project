<template>
    <nav class="profile-nav" aria-label="بخش‌های پروفایل">
      <router-link
        v-for="tab in TABS"
        :key="tab.id"
        :to="`/profile` + tab.link"
        type="button"
        class="profile-nav__item"
        :class="{ 'profile-nav__item--active': activeTab === tab.id }"
        :aria-current="activeTab === tab.id ? 'page' : undefined"
        @click="activeTab = tab.id"
      >
        <font-awesome-icon :icon="['fa-solid', tab.icon]" class="edit-icon" />
        {{ tab.label }}
      </router-link>
      <button
        type="button"
        class="profile-nav__item profile-nav__item--danger"
        @click="submitLogoutForm"
      >
        <font-awesome-icon icon="fa-solid fa-sign-out" class="logout-icon" />
        {{ $t("Logout") }}
      </button>
    </nav>
</template>

<script>
import { ref } from "vue";
import { http } from "../../../utils/http";
export default {
  inject: ["auth"],
  setup() {
    const TABS = [
      { id: "dashboard", label: "پیشخوان", link: "", icon: "fa-user-circle" },
      {
        id: "two-factor",
        label: "احراز هویت دو مرحله ای",
        link: "/twofactor",
        icon: "fa-edit",
      },
      { id: "orders", label: "سفارش‌ها", link: "/orders", icon: "fa-boxes" },
      {
        id: "edit",
        label: "ویرایش حساب کاربری",
        link: "/edit-account",
        icon: "fa-edit",
      },
    ];
    const activeTab = ref("dashboard");

    async function submitLogoutForm() {
      await http("/logout", {
        method: "POST",
      });

      window.location.href = "/";
    }

    return {
      TABS,
      activeTab,
      submitLogoutForm,
    };
  },
};
</script>

<style scoped>

</style>