import { ref } from "vue";

export function usePrivacyPolicyAppData() {
    const el = document.querySelector("#privacy-policy-app");

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
        privacyPolicyIsAuthenticated: isAuthenticated,
    };
}
