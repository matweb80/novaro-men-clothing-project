import { ref } from "vue";

export function useCheckoutAppData() {
    const el = document.querySelector("#checkout-app");

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
        checkoutIsAuthenticated: isAuthenticated,
    };
}
