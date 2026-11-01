import { createApp, defineAsyncComponent } from "vue";
import { createI18n } from "vue-i18n";
import { createPinia } from "pinia";

const NotFoundPage = defineAsyncComponent(
    () => import("@/components/pages/NotFoundPage.vue"),
);

// Fontawesome Icons
import { library } from "@fortawesome/fontawesome-svg-core";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";
import { fab } from "@fortawesome/free-brands-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(fas, far, fab);
//
import "@/assets/styles/tokens.css";
import "@/assets/styles/components.css";

async function loadMessages(locale) {
    return await fetch(`/api/vue/translations/${locale}`)
        .then((response) => response.json())
        .then((data) => data);
}

(async () => {
    const locale = "fa"; // یا بر اساس تنظیم کاربر
    const messages = await loadMessages(locale);

    const i18n = createI18n({
        legacy: false,
        locale,
        fallbackLocale: "en",
        messages: {
            [locale]: messages,
        },
    });

    // App Vue
    const app = createApp(NotFoundPage);
    const pinia = createPinia();

    // Fontawesome Icons Component
    app.component("font-awesome-icon", FontAwesomeIcon);

    app.use(pinia);
    app.use(i18n);
    app.mount("#not-found-app");
})();
