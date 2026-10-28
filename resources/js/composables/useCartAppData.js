import { ref } from "vue";

export function useCartAppData() {
    const el = document.querySelector("#cart-app");

    if (!el) {
        console.warn("Products App root element not found!");
        return {};
    }

    const safeParse = (data) => {
        try {
            return JSON.parse(data);
        } catch {
            return data;
        }
    };

    const user = ref(safeParse(el.dataset.user));
    const isAuthenticated = ref(safeParse(el.dataset.auth));

    return {
        cartUser: user,
        cartIsAuthenticated: isAuthenticated,
    };
}
