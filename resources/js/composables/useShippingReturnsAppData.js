import { ref } from "vue";

export function useShippingReturnsAppData() {
    const el = document.querySelector("#shipping-returns-app");

    if (!el) {
        console.warn("App root element not found!");
        return {};
    }

    const safeParse = (data) => {
        try {
            return JSON.parse(data);
        } catch {
            return data;
        }
    };
    const isAuthenticated = ref(safeParse(el.dataset.auth));

    return {
        shippingReturnsIsAuthenticated: isAuthenticated,
    };
}
