import { ref } from "vue";

export function useProductPageAppData() {
    const el = document.querySelector("#product-page-app");

    if (!el) {
        console.warn("Product Page App root element not found!");
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
    const slug = el.dataset.slug;


    return {
        productPageUser: user,
        productPageIsAuthenticated: isAuthenticated,
        slug,
    };
}
