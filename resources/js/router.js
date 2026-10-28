import { defineAsyncComponent } from "vue";
import { createRouter, createWebHistory } from "vue-router";

import MainPage from "./components/pages/MainPage.vue";
import ProductsApp from "./ProductsApp.vue";

const Blog = defineAsyncComponent(() =>
    import("./components/Main/header-routes-components/Blog.vue")
);

const Products = defineAsyncComponent(() =>
    import("./components/Main/header-routes-components/Products.vue")
);

const Services = defineAsyncComponent(() =>
    import("./components/Main/header-routes-components/Services.vue")
);

const ContactUs = defineAsyncComponent(() =>
    import("./components/Main/header-routes-components/ContactUs.vue")
);

const AboutUs = defineAsyncComponent(() =>
    import("./components/Main/header-routes-components/AboutUs.vue")
);

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: "/", component: MainPage },
        { path: "/blog", component: Blog },
        { path: "/shop", component: ProductsApp },
        { path: "/services", component: Services },
        { path: "/contact-us", component: ContactUs },
        { path: "/about-us", component: AboutUs },
        { path: "/profile", component: AboutUs },
    ],
});

export default router;
