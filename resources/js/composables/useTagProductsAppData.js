import { ref } from "vue";

export function useTagProductsAppData() {
    const el = document.querySelector("#tag-products-app");

    if (!el) {
        console.warn("Tag Product App root element not found!");
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
        tagProductsIsAuthenticated: isAuthenticated,
        tagSlug: slug,
    };
}
