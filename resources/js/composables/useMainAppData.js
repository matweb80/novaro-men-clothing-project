import { onMounted, ref } from "vue";

export function useMainAppData() {
    const el = document.querySelector("#app");

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
        mainIsAuthenticated: isAuthenticated,
    };
}
