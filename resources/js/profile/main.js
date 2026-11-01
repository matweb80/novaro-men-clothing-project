import { createApp, defineAsyncComponent } from "vue";
import { createI18n } from "vue-i18n";
import { createPinia } from "pinia";
import router from "./router.js";

const App = defineAsyncComponent(() => import("./App.vue"));

const BaseButton = defineAsyncComponent(
    () => import("../components/UI/BaseButton.vue"),
);

const BaseSection = defineAsyncComponent(
    () => import("./components/UI/BaseSection.vue"),
);

const BaseLogo = defineAsyncComponent(
    () => import("../components/UI/BaseLogo.vue"),
);

const BaseSocialIcons = defineAsyncComponent(
    () => import("../components/UI/BaseSocialIcons.vue"),
);

const BaseSpinner = defineAsyncComponent(
    () => import("../components/UI/BaseSpinner.vue"),
);

const BaseDialog = defineAsyncComponent(
    () => import("../components/UI/BaseDialog.vue"),
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
    const app = createApp(App);
    const pinia = createPinia();

    app.component("base-button", BaseButton);
    app.component("base-section", BaseSection);
    app.component("base-logo", BaseLogo);
    app.component("base-social-icons", BaseSocialIcons);
    app.component("base-spinner", BaseSpinner);
    app.component("base-dialog", BaseDialog);

    // Fontawesome Icons Component
    app.component("font-awesome-icon", FontAwesomeIcon);

    app.use(pinia);
    app.use(router);
    app.use(i18n);
    app.mount("#profile-page-app");
})();
