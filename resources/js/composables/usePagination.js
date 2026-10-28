import { computed } from "vue";
import { useFilteredProducts } from "./useFilteredProducts";

export function usePagination() {
    const { currentPage, lastPage, applyFilters } = useFilteredProducts();

    // تغییر صفحه
    const changePage = (page) => {
        if (page < 1 || page > lastPage) return;
        applyFilters(page);
        window.scrollTo({ top: 0, behavior: "smooth" });
    };

    const visiblePages = computed(() => {
        const total = lastPage;
        const current = currentPage;
        const pages = [];
        if (total <= 7) for (let i = 1; i <= total; i++) pages.push(i);
        else {
            pages.push(1);
            if (current > 4) pages.push("...");
            const start = Math.max(2, current - 2);
            const end = Math.min(total - 1, current + 2);
            for (let i = start; i <= end; i++) pages.push(i);
            if (current < total - 3) pages.push("...");
            pages.push(total);
        }
        return pages;
    });

    return {
            changePage,
            visiblePages,
            currentPage,
            lastPage,
    };
}
