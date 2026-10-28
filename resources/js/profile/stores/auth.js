import { defineStore } from "pinia";
import { useCheckAppData } from "../../composables/useCheckAppData";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        user: null,
    }),
    getters: {
        isAuthenticated: (state) => !!state.user,
    },
    actions: {
        fetchUser() {
            const { user } = useCheckAppData();
            this.user = user.value;
        },
    },
});
