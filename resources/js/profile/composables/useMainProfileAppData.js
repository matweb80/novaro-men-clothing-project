import { ref } from "vue";

export function useMainProfileAppData() {
    const el = document.querySelector("#profile-page-app");

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
    const user = ref(safeParse(el.dataset.user));
    const isAuthenticated = ref(safeParse(el.dataset.auth));

    return {
        mainProfileUser: user,
        mainProfileIsAuthenticated: isAuthenticated,
    };
}
