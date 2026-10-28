import { ref } from "vue";

export function useCategoryProductsAppData() {
    const el = document.querySelector("#category-products-app");

    if (!el) {
        console.warn("Category Product App root element not found!");
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
    const slug = el.dataset.slug;

    return {
        categoryProductsIsAuthenticated: isAuthenticated,
        categorySlug: slug,
    };
}
