<template>
  <!-- 📄 Pagination -->
  <div v-if="lastPage > 1" class="pagination">
    <button
      type="button"
      @click="changePage(currentPage - 1)"
      :disabled="currentPage <= 1"
      class="pagination__btn"
      aria-label="صفحه قبل"
    >
      <font-awesome-icon
        icon="fa-solid fa-chevron-right fa-lg"
        aria-hidden="true"
        class="chev"
      />
    </button>

    <template v-for="page in visiblePages" :key="page">
      <span v-if="page === '...'" class="pagination__dot">...</span>
      <button
        v-else
        @click="changePage(page)"
        type="button"
        class="pagination__btn"
        :aria-current="page === currentPage ? 'page' : undefined"
        :aria-label="`صفحه ${currentPage}`"
      >
        {{ page }}
      </button>
    </template>

    <button
      type="button"
      @click="changePage(currentPage + 1)"
      :disabled="currentPage >= lastPage"
      class="pagination__btn"
      aria-label="صفحه بعد"
    >
      <font-awesome-icon
        icon="fa-solid fa-chevron-left fa-lg"
        aria-hidden="true"
        class="chev"
      />
    </button>
  </div>
</template>

<script>
export default {
  inject: ["visiblePages", "currentPage", "lastPage", "changePage"],
};
</script>