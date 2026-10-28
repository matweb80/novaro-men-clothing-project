import { computed, ref } from "vue";
import { http } from "../../utils/http";

const userOrders = ref(null);
const isLoading = ref(false);

const order = ref(null);
const orderProducts = ref(null);
const address = ref(null);

const userPhone = ref("");
const userTwoFactorType = ref(null);

const currentPage = ref(1);
const lastPage = ref(1);

export function useOrders() {
    // Pagination
    // تغییر صفحه
    const changePage = (page) => {
        if (page < 1 || page > lastPage.value) return;
        fetchOrders(page);
        window.scrollTo({ top: 0, behavior: "smooth" });
    };

    const visiblePages = computed(() => {
        const total = lastPage.value;
        const current = currentPage.value;
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

    // create query string from filters
    function buildQueryString() {
        const queryParams = new URLSearchParams();

        if (currentPage.value > 1)
            queryParams.append("page", currentPage.value);

        return queryParams.toString();
    }

    // Get filters from URL
    function loadFiltersFromUrl() {
        const params = new URLSearchParams(window.location.search);

        const page = parseInt(params.get("page")) || 1;
        currentPage.value = page;
    }

    const fetchOrders = async (page = 1, pushToUrl = true) => {
        try {
            isLoading.value = true;
            currentPage.value = page;

            const queryString = buildQueryString();
            const res = await http(`/user?${queryString}`);

            const data = await res.json();

            if (res.ok) {
                userOrders.value = data.orders.data;
                lastPage.value = data.orders.last_page ?? 1;
                userPhone.value = data.user_phone_number;
                userTwoFactorType.value = data.user_two_factor_type;
            }

            // ذخیره فیلترها در URL
            if (pushToUrl) {
                const newUrl = `${window.location.pathname}?${queryString}`;
                window.history.pushState({}, "", newUrl);
            }
        } catch (error) {
            console.error("Failed to fetch data: ", error);
        } finally {
            isLoading.value = false;
        }
    };

    const fetchOrderDetails = async (id, page = 1, pushToUrl = true) => {
        try {
            isLoading.value = true;
            currentPage.value = page;

            const queryString = buildQueryString();
            const res = await http(`/orders/${id}`);

            const data = await res.json();

            if (res.ok) {
                order.value = data.order;
                orderProducts.value = data.orderProducts;
                address.value = data.address;
            }

            // ذخیره فیلترها در URL
            if (pushToUrl) {
                const newUrl = `${window.location.pathname}?${queryString}`;
                window.history.pushState({}, "", newUrl);
            }
        } catch (error) {
            console.error("Failed to fetch data: ", error);
        } finally {
            isLoading.value = false;
        }
    };

    return {
        userOrders,
        isLoading,
        currentPage,
        lastPage,
        changePage,
        visiblePages,
        loadFiltersFromUrl,
        fetchOrders,
        fetchOrderDetails,
        order,
        orderProducts,
        address,
        userPhone,
        userTwoFactorType,
    };
}
