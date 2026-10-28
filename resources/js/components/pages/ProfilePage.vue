<script setup>
import { computed, reactive, ref } from "vue";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";

import ProfileLayout from "@/profile/components/layouts/ProfileLayout.vue";
import TheSidebar from "@/profile/components/layouts/TheSidebar.vue";
import NowTime from "@/profile/components/NowTime/NowTime.vue";
import { http } from "@/utils/http.js";

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

const activeTabLabel = computed(
  () => TABS.find((t) => t.id === activeTab.value)?.label ?? ""
);
</script>

<template>
  <main id="main">
    <div class="page-hero container">
      <Breadcrumb current-label="پروفایل من" />
      <p class="eyebrow">حساب کاربری</p>
      <h1 class="page-hero__title">{{ activeTabLabel }}</h1>
    </div>

    <section class="profile container">
      <div class="profile__grid">
        <!-- ناوبری داخلی پروفایل -->
        <the-sidebar></the-sidebar>

        <div class="profile-panel">
          <profile-layout> </profile-layout>
          <now-time></now-time>
        </div>
      </div>
    </section>
  </main>
</template>

<style scoped>

</style>
