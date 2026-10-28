import { ref } from "vue";

export function useProductsAppData() {
    const el = document.querySelector("#products-app");

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

    const isAuthenticated = ref(safeParse(el.dataset.auth));

    return {
        productsIsAuthenticated: isAuthenticated,
    };
}
