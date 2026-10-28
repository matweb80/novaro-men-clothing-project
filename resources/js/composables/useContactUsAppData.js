import { ref } from "vue";

export function useContactUsAppData() {
    const el = document.querySelector("#contact-us-app");

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
        contactUsIsAuthenticated: isAuthenticated,
    };
}
