import { defineAsyncComponent } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import { http } from "../utils/http";

const ProfileLayout = defineAsyncComponent(
    () => import("./components/layouts/ProfileLayout.vue"),
);
const Dashboard = defineAsyncComponent(() => import("./pages/Dashboard.vue"));
const Orders = defineAsyncComponent(() => import("./pages/Orders.vue"));
const OrderDetails = defineAsyncComponent(
    () => import("./pages/OrderDetails.vue"),
);
const OrderPayment = defineAsyncComponent(
    () => import("./pages/OrderPayment.vue"),
);
const TwoFactor = defineAsyncComponent(() => import("./pages/TwoFactor.vue"));
const TokenPhone = defineAsyncComponent(() => import("./pages/TokenPhone.vue"));
const NotFound = defineAsyncComponent(() => import("./pages/NotFound.vue"));
const EditAccount = defineAsyncComponent(
    () => import("./pages/EditAccount.vue"),
);

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/profile",
            component: ProfileLayout,
            meta: { requiresAuth: true },
            children: [
                {
                    path: "",
                    component: Dashboard,
                    meta: { requiresAuth: true },
                },
                {
                    path: "orders",
                    component: Orders,
                    meta: { requiresAuth: true },
                },
                {
                    path: "orders/:id",
                    component: OrderDetails,
                    props: true,
                    children: [
                        { path: "payment", component: OrderPayment }, //orders/22/payment
                    ],
                    beforeEnter: async (to, _, next) => {
                        try {
                            await http(`/orders/${to.params.id}`)
                            next();
                        } catch (e) {
                            next({ name: "profile-404" });
                        }
                    },
                },
                {
                    path: "twofactor",
                    component: TwoFactor,
                    meta: { requiresAuth: true },
                },
                {
                    path: "twofactor/phone",
                    component: TokenPhone,
                    meta: { requiresAuth: true },
                },
                {
                    path: "edit-account",
                    component: EditAccount,
                    meta: { requiresAuth: true },
                },
                {
                    path: ":pathMatch(.*)*",
                    name: "profile-404",
                    component: NotFound,
                },
            ],
        },
    ],
});

export default router;
