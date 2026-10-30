import "./bootstrap";
import "@/assets/styles/tokens.css";
import "@/assets/styles/components.css";

import { createApp, defineAsyncComponent } from "vue";
import { createI18n } from "vue-i18n";

const BaseCard = defineAsyncComponent(
    () => import("./components/UI/BaseCard.vue"),
);
const BaseButton = defineAsyncComponent(
    () => import("./components/UI/BaseButton.vue"),
);

const BaseButtonOutline = defineAsyncComponent(
    () => import("./components/UI/BaseButtonOutline.vue"),
);

const BaseBox = defineAsyncComponent(
    () => import("./components/UI/BaseBox.vue"),
);

const BaseSlider = defineAsyncComponent(
    () => import("./components/UI/BaseSlider.vue"),
);

const BaseLogo = defineAsyncComponent(
    () => import("./components/UI/BaseLogo.vue"),
);

const BaseMobileMenuModal = defineAsyncComponent(
    () => import("./components/UI/BaseMobileMenuModal.vue"),
);

const BaseDialog = defineAsyncComponent(
    () => import("./components/UI/BaseDialog.vue"),
);

const BaseSearch = defineAsyncComponent(
    () => import("./components/UI/BaseSearch.vue"),
);

const BaseSpinner = defineAsyncComponent(
    () => import("./components/UI/BaseSpinner.vue"),
);

const BaseSocialIcons = defineAsyncComponent(
    () => import("./components/UI/BaseSocialIcons.vue"),
);

const BaseFadeOnScroll = defineAsyncComponent(
    () => import("./components/UI/BaseFadeOnScroll.vue"),
);

const BaseMiniDialog = defineAsyncComponent(
    () => import("./components/UI/BaseMiniDialog.vue"),
);

const BaseCartDrawer = defineAsyncComponent(
    () => import("./components/UI/BaseCartDrawer.vue"),
);

const BaseBreadcrumb = defineAsyncComponent(
    () => import("./components/UI/BaseBreadcrumb.vue"),
);

// import App from "./App.vue";
const App = defineAsyncComponent(() => import("./App.vue"));
const ProductsApp = defineAsyncComponent(() => import("./ProductsApp.vue"));
const ProductPageApp = defineAsyncComponent(
    () => import("./ProductPageApp.vue"),
);
const ContactUsApp = defineAsyncComponent(() => import("./ContactUsApp.vue"));
const AboutUsApp = defineAsyncComponent(() => import("./AboutUsApp.vue"));
const CategoryProductsApp = defineAsyncComponent(
    () => import("./CategoryProductsApp.vue"),
);
const TagProductsApp = defineAsyncComponent(
    () => import("./TagProductsApp.vue"),
);
const CheckoutApp = defineAsyncComponent(() => import("./CheckoutApp.vue"));
const CartApp = defineAsyncComponent(() => import("./CartApp.vue"));
const ShippingReturnsApp = defineAsyncComponent(
    () => import("./ShippingReturnsApp.vue"),
);
const PrivacyPolicyApp = defineAsyncComponent(
    () => import("./PrivacyPolicyApp.vue"),
);
const TermsApp = defineAsyncComponent(
    () => import("./TermsApp.vue"),
);

import { useProductPageAppData } from "./composables/useProductPageAppData.js";
import { useCategoryProductsAppData } from "./composables/useCategoryProductsAppData.js";
import { useTagProductsAppData } from "./composables/useTagProductsAppData.js";

// Fontawesome Icons
import { library } from "@fortawesome/fontawesome-svg-core";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";
import { fab } from "@fortawesome/free-brands-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(fas, far, fab);
//

// AOS (Animate On Scroll)
import AOS from "aos";
import "aos/dist/aos.css";

AOS.init();

//splide slider
import "@splidejs/vue-splide/css";
import { Splide, SplideSlide } from "@splidejs/vue-splide";
//photo swipe
import "photoswipe/style.css";
import "../css/photoswipe-custom.css";
import novaroFavicon from "./assets/novaro-favicon.webp";

const favicon =
    document.querySelector("link[rel~='icon']") ||
    document.createElement("link");
favicon.rel = "icon";
favicon.type = "image/webp";
favicon.href = novaroFavicon;
if (!favicon.parentNode) document.head.appendChild(favicon);

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

    app.component("base-card", BaseCard);
    app.component("base-button", BaseButton);
    app.component("base-button-outline", BaseButtonOutline);
    app.component("base-box", BaseBox);
    app.component("base-slider", BaseSlider);
    app.component("base-logo", BaseLogo);
    app.component("base-mobile-menu-modal", BaseMobileMenuModal);
    app.component("base-dialog", BaseDialog);
    app.component("base-search", BaseSearch);
    app.component("base-spinner", BaseSpinner);
    app.component("base-social-icons", BaseSocialIcons);
    app.component("base-fade-on-scroll", BaseFadeOnScroll);
    app.component("base-mini-dialog", BaseMiniDialog);
    app.component("base-cart-drawer", BaseCartDrawer);
    app.component("base-breadcrumb", BaseBreadcrumb);
    app.component("Splide", Splide);
    app.component("SplideSlide", SplideSlide);

    // Fontawesome Icons Component
    app.component("font-awesome-icon", FontAwesomeIcon);

    app.use(i18n);
    app.mount("#app");

    // Products App Vue
    const productsApp = createApp(ProductsApp);

    productsApp.component("base-card", BaseCard);
    productsApp.component("base-button", BaseButton);
    productsApp.component("base-button-outline", BaseButtonOutline);
    productsApp.component("base-box", BaseBox);
    productsApp.component("base-slider", BaseSlider);
    productsApp.component("base-logo", BaseLogo);
    productsApp.component("base-mobile-menu-modal", BaseMobileMenuModal);
    productsApp.component("base-dialog", BaseDialog);
    productsApp.component("base-search", BaseSearch);
    productsApp.component("base-spinner", BaseSpinner);
    productsApp.component("base-social-icons", BaseSocialIcons);
    productsApp.component("base-fade-on-scroll", BaseFadeOnScroll);
    productsApp.component("base-mini-dialog", BaseMiniDialog);
    productsApp.component("base-cart-drawer", BaseCartDrawer);
    productsApp.component("base-breadcrumb", BaseBreadcrumb);

    // Fontawesome Icons Component
    productsApp.component("font-awesome-icon", FontAwesomeIcon);

    productsApp.use(i18n);
    productsApp.mount("#products-app");

    // Product Page App Vue
    const { slug } = useProductPageAppData();
    const productPageApp = createApp(ProductPageApp, { slug });

    productPageApp.component("base-card", BaseCard);
    productPageApp.component("base-button", BaseButton);
    productPageApp.component("base-button-outline", BaseButtonOutline);
    productPageApp.component("base-box", BaseBox);
    productPageApp.component("base-slider", BaseSlider);
    productPageApp.component("base-logo", BaseLogo);
    productPageApp.component("base-mobile-menu-modal", BaseMobileMenuModal);
    productPageApp.component("base-dialog", BaseDialog);
    productPageApp.component("base-search", BaseSearch);
    productPageApp.component("base-spinner", BaseSpinner);
    productPageApp.component("base-social-icons", BaseSocialIcons);
    productPageApp.component("base-fade-on-scroll", BaseFadeOnScroll);
    productPageApp.component("base-mini-dialog", BaseMiniDialog);
    productPageApp.component("base-cart-drawer", BaseCartDrawer);
    productPageApp.component("base-breadcrumb", BaseBreadcrumb);
    productPageApp.component("Splide", Splide);
    productPageApp.component("SplideSlide", SplideSlide);

    // Fontawesome Icons Component
    productPageApp.component("font-awesome-icon", FontAwesomeIcon);

    productPageApp.use(i18n);
    productPageApp.mount("#product-page-app");

    // Contact Us App Vue
    const contactUsApp = createApp(ContactUsApp);

    contactUsApp.component("base-card", BaseCard);
    contactUsApp.component("base-button", BaseButton);
    contactUsApp.component("base-button-outline", BaseButtonOutline);
    contactUsApp.component("base-box", BaseBox);
    contactUsApp.component("base-slider", BaseSlider);
    contactUsApp.component("base-logo", BaseLogo);
    contactUsApp.component("base-mobile-menu-modal", BaseMobileMenuModal);
    contactUsApp.component("base-dialog", BaseDialog);
    contactUsApp.component("base-search", BaseSearch);
    contactUsApp.component("base-spinner", BaseSpinner);
    contactUsApp.component("base-social-icons", BaseSocialIcons);
    contactUsApp.component("base-fade-on-scroll", BaseFadeOnScroll);
    contactUsApp.component("base-mini-dialog", BaseMiniDialog);
    contactUsApp.component("base-cart-drawer", BaseCartDrawer);
    contactUsApp.component("base-breadcrumb", BaseBreadcrumb);

    // Fontawesome Icons Component
    contactUsApp.component("font-awesome-icon", FontAwesomeIcon);

    contactUsApp.use(i18n);
    contactUsApp.mount("#contact-us-app");

    // About Us App Vue
    const aboutUsApp = createApp(AboutUsApp);

    aboutUsApp.component("base-card", BaseCard);
    aboutUsApp.component("base-button", BaseButton);
    aboutUsApp.component("base-button-outline", BaseButtonOutline);
    aboutUsApp.component("base-box", BaseBox);
    aboutUsApp.component("base-slider", BaseSlider);
    aboutUsApp.component("base-logo", BaseLogo);
    aboutUsApp.component("base-mobile-menu-modal", BaseMobileMenuModal);
    aboutUsApp.component("base-dialog", BaseDialog);
    aboutUsApp.component("base-search", BaseSearch);
    aboutUsApp.component("base-spinner", BaseSpinner);
    aboutUsApp.component("base-social-icons", BaseSocialIcons);
    aboutUsApp.component("base-fade-on-scroll", BaseFadeOnScroll);
    aboutUsApp.component("base-mini-dialog", BaseMiniDialog);
    aboutUsApp.component("base-cart-drawer", BaseCartDrawer);
    aboutUsApp.component("base-breadcrumb", BaseBreadcrumb);

    // Fontawesome Icons Component
    aboutUsApp.component("font-awesome-icon", FontAwesomeIcon);

    aboutUsApp.use(i18n);
    aboutUsApp.mount("#about-us-app");

    // Category Products App Vue
    const { categorySlug } = useCategoryProductsAppData();
    const categoryProductsApp = createApp(CategoryProductsApp, {
        categorySlug,
    });

    categoryProductsApp.component("base-card", BaseCard);
    categoryProductsApp.component("base-button", BaseButton);
    categoryProductsApp.component("base-button-outline", BaseButtonOutline);
    categoryProductsApp.component("base-box", BaseBox);
    categoryProductsApp.component("base-slider", BaseSlider);
    categoryProductsApp.component("base-logo", BaseLogo);
    categoryProductsApp.component(
        "base-mobile-menu-modal",
        BaseMobileMenuModal,
    );
    categoryProductsApp.component("base-dialog", BaseDialog);
    categoryProductsApp.component("base-search", BaseSearch);
    categoryProductsApp.component("base-spinner", BaseSpinner);
    categoryProductsApp.component("base-social-icons", BaseSocialIcons);
    categoryProductsApp.component("base-fade-on-scroll", BaseFadeOnScroll);
    categoryProductsApp.component("base-mini-dialog", BaseMiniDialog);
    categoryProductsApp.component("base-cart-drawer", BaseCartDrawer);
    categoryProductsApp.component("base-breadcrumb", BaseBreadcrumb);

    // Fontawesome Icons Component
    categoryProductsApp.component("font-awesome-icon", FontAwesomeIcon);

    categoryProductsApp.use(i18n);
    categoryProductsApp.mount("#category-products-app");

    // Tag Products App Vue
    const { tagSlug } = useTagProductsAppData();
    const tagProductsApp = createApp(TagProductsApp, { tagSlug });

    tagProductsApp.component("base-card", BaseCard);
    tagProductsApp.component("base-button", BaseButton);
    tagProductsApp.component("base-button-outline", BaseButtonOutline);
    tagProductsApp.component("base-box", BaseBox);
    tagProductsApp.component("base-slider", BaseSlider);
    tagProductsApp.component("base-logo", BaseLogo);
    tagProductsApp.component("base-mobile-menu-modal", BaseMobileMenuModal);
    tagProductsApp.component("base-dialog", BaseDialog);
    tagProductsApp.component("base-search", BaseSearch);
    tagProductsApp.component("base-spinner", BaseSpinner);
    tagProductsApp.component("base-social-icons", BaseSocialIcons);
    tagProductsApp.component("base-fade-on-scroll", BaseFadeOnScroll);
    tagProductsApp.component("base-mini-dialog", BaseMiniDialog);
    tagProductsApp.component("base-cart-drawer", BaseCartDrawer);
    tagProductsApp.component("base-breadcrumb", BaseBreadcrumb);

    // Fontawesome Icons Component
    tagProductsApp.component("font-awesome-icon", FontAwesomeIcon);

    tagProductsApp.use(i18n);
    tagProductsApp.mount("#tag-products-app");

    // Checkout App Vue
    const checkoutApp = createApp(CheckoutApp);

    checkoutApp.component("base-card", BaseCard);
    checkoutApp.component("base-button", BaseButton);
    checkoutApp.component("base-button-outline", BaseButtonOutline);
    checkoutApp.component("base-box", BaseBox);
    checkoutApp.component("base-slider", BaseSlider);
    checkoutApp.component("base-logo", BaseLogo);
    checkoutApp.component("base-mobile-menu-modal", BaseMobileMenuModal);
    checkoutApp.component("base-dialog", BaseDialog);
    checkoutApp.component("base-search", BaseSearch);
    checkoutApp.component("base-spinner", BaseSpinner);
    checkoutApp.component("base-social-icons", BaseSocialIcons);
    checkoutApp.component("base-fade-on-scroll", BaseFadeOnScroll);
    checkoutApp.component("base-mini-dialog", BaseMiniDialog);
    checkoutApp.component("base-cart-drawer", BaseCartDrawer);
    checkoutApp.component("base-breadcrumb", BaseBreadcrumb);

    // Fontawesome Icons Component
    checkoutApp.component("font-awesome-icon", FontAwesomeIcon);

    checkoutApp.use(i18n);
    checkoutApp.mount("#checkout-app");

    // Cart App Vue
    const cartApp = createApp(CartApp);

    cartApp.component("base-card", BaseCard);
    cartApp.component("base-button", BaseButton);
    cartApp.component("base-button-outline", BaseButtonOutline);
    cartApp.component("base-box", BaseBox);
    cartApp.component("base-slider", BaseSlider);
    cartApp.component("base-logo", BaseLogo);
    cartApp.component("base-mobile-menu-modal", BaseMobileMenuModal);
    cartApp.component("base-dialog", BaseDialog);
    cartApp.component("base-search", BaseSearch);
    cartApp.component("base-spinner", BaseSpinner);
    cartApp.component("base-social-icons", BaseSocialIcons);
    cartApp.component("base-fade-on-scroll", BaseFadeOnScroll);
    cartApp.component("base-mini-dialog", BaseMiniDialog);
    cartApp.component("base-cart-drawer", BaseCartDrawer);
    cartApp.component("base-breadcrumb", BaseBreadcrumb);

    // Fontawesome Icons Component
    cartApp.component("font-awesome-icon", FontAwesomeIcon);

    cartApp.use(i18n);
    cartApp.mount("#cart-app");

    // Shipping Returns App Vue
    const shippingReturnsApp = createApp(ShippingReturnsApp);

    shippingReturnsApp.component("base-button", BaseButton);
    shippingReturnsApp.component("base-logo", BaseLogo);
    shippingReturnsApp.component("base-search", BaseSearch);
    shippingReturnsApp.component("base-social-icons", BaseSocialIcons);
    shippingReturnsApp.component("base-fade-on-scroll", BaseFadeOnScroll);
    shippingReturnsApp.component("base-cart-drawer", BaseCartDrawer);
    shippingReturnsApp.component("base-breadcrumb", BaseBreadcrumb);

    // Fontawesome Icons Component
    shippingReturnsApp.component("font-awesome-icon", FontAwesomeIcon);

    shippingReturnsApp.use(i18n);
    shippingReturnsApp.mount("#shipping-returns-app");

    // Privacy Policy App Vue
    const privacyPolicyApp = createApp(PrivacyPolicyApp);

    privacyPolicyApp.component("base-button", BaseButton);
    privacyPolicyApp.component("base-logo", BaseLogo);
    privacyPolicyApp.component("base-search", BaseSearch);
    privacyPolicyApp.component("base-social-icons", BaseSocialIcons);
    privacyPolicyApp.component("base-fade-on-scroll", BaseFadeOnScroll);
    privacyPolicyApp.component("base-cart-drawer", BaseCartDrawer);
    privacyPolicyApp.component("base-breadcrumb", BaseBreadcrumb);

    // Fontawesome Icons Component
    privacyPolicyApp.component("font-awesome-icon", FontAwesomeIcon);

    privacyPolicyApp.use(i18n);
    privacyPolicyApp.mount("#privacy-policy-app");

    // Terms App Vue
    const termsApp = createApp(TermsApp);

    termsApp.component("base-button", BaseButton);
    termsApp.component("base-logo", BaseLogo);
    termsApp.component("base-search", BaseSearch);
    termsApp.component("base-social-icons", BaseSocialIcons);
    termsApp.component("base-fade-on-scroll", BaseFadeOnScroll);
    termsApp.component("base-cart-drawer", BaseCartDrawer);
    termsApp.component("base-breadcrumb", BaseBreadcrumb);

    // Fontawesome Icons Component
    termsApp.component("font-awesome-icon", FontAwesomeIcon);

    termsApp.use(i18n);
    termsApp.mount("#terms-app");
})();
