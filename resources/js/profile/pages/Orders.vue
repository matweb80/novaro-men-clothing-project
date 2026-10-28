<template>
  <div class="orders">
    <!-- 🌀 Loader -->
    <div v-if="isLoading">
      <base-spinner></base-spinner>
    </div>
    <ul v-if="!isLoading && userOrders?.length != 0" class="order-list">
      <li v-for="order in userOrders" :key="order.id">
        <OrderCard :order="order" />
      </li>
    </ul>
    <pagination
      class="orders-pagination"
      v-if="!isLoading && userOrders?.length != 0"
    ></pagination>
    <div v-if="!isLoading && userOrders?.length == 0" class="empty-state">
      <svg class="empty-state__icon" viewBox="0 0 24 24" aria-hidden="true">
        <rect x="4" y="7" width="16" height="13" rx="2" />
        <path d="M8 7V5a4 4 0 0 1 8 0v2" />
      </svg>
      <p class="empty-state__text">هنوز سفارشی ثبت نکرده‌اید.</p>
      <a href="/products" class="btn btn--outline btn--sm">مشاهده فروشگاه</a>
    </div>
  </div>
</template>

<script>
import { onMounted, provide } from "vue";
import Pagination from "@/components/shop/Pagination.vue";
import { useOrders } from "../composables/useOrders";
import OrderCard from "../components/orders/OrderCard.vue";

export default {
  components: { Pagination, OrderCard },
  setup() {
    const {
      userOrders,
      isLoading,
      currentPage,
      lastPage,
      changePage,
      visiblePages,
      loadFiltersFromUrl,
      fetchOrders,
    } = useOrders();

    function formatDate(dateStr) {
      return new Date(dateStr).toLocaleDateString("fa-IR", {
        year: "numeric",
        month: "long",
        day: "numeric",
      });
    }

    onMounted(async () => {
      loadFiltersFromUrl();
      fetchOrders(currentPage.value, false);
    });

    // بازخوانی هنگام Back / Forward مرورگر
    window.addEventListener("popstate", () => {
      loadFiltersFromUrl();
      fetchOrders(currentPage.value, false);
    });

    provide("visiblePages", visiblePages);
    provide("currentPage", currentPage);
    provide("lastPage", lastPage);
    provide("changePage", changePage);

    return {
      userOrders,
      formatDate,
      isLoading,
      currentPage,
      lastPage,
      changePage,
      visiblePages,
    };
  },
};
</script>

<style scoped>
.order-actions-items {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.orders-pagination {
  margin: 16px auto;
}

/* --- سفارش‌ها --- */
.order-list {
  display: flex;
  flex-direction: column;
  gap: var(--sp-4);
}
</style>