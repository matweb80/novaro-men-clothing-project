import { computed } from "vue";
import { useMainAppData } from "../composables/useMainAppData.js";
import { useProductsAppData } from "../composables/useProductsAppData.js";
import { useProductPageAppData } from "../composables/useProductPageAppData.js";
import { useContactUsAppData } from "./useContactUsAppData.js";
import { useAboutUsAppData } from "./useAboutUsAppData.js";
import { useCategoryProductsAppData } from "./useCategoryProductsAppData.js";
import { useTagProductsAppData } from "./useTagProductsAppData.js";
import { useCheckoutAppData } from "./useCheckoutAppData.js";
import { useMainProfileAppData } from "../profile/composables/useMainProfileAppData.js";
import { useCartAppData } from "./useCartAppData.js";
import { useShippingReturnsAppData } from "./useShippingReturnsAppData.js";
import { usePrivacyPolicyAppData } from "./usePrivacyPolicyAppData.js";
import { useTermsAppData } from "./useTermsAppData.js";
import { useNotFoundAppData } from "./useNotFoundAppData.js";

export function useCheckAppData() {
    const { mainIsAuthenticated } = useMainAppData();
    const { productsIsAuthenticated } = useProductsAppData();
    const { productPageUser, productPageIsAuthenticated } =
        useProductPageAppData();

    const { contactUsIsAuthenticated } = useContactUsAppData();

    const { aboutUsIsAuthenticated } = useAboutUsAppData();

    const { categoryProductsIsAuthenticated } = useCategoryProductsAppData();

    const { tagProductsIsAuthenticated } = useTagProductsAppData();

    const { checkoutIsAuthenticated } = useCheckoutAppData();

    const { mainProfileUser, mainProfileIsAuthenticated } =
        useMainProfileAppData();

    const { cartUser, cartIsAuthenticated } = useCartAppData();

    const { shippingReturnsIsAuthenticated } = useShippingReturnsAppData();

    const { privacyPolicyIsAuthenticated } = usePrivacyPolicyAppData();

    const { termsIsAuthenticated } = useTermsAppData();

    const { notFoundIsAuthenticated } = useNotFoundAppData();

    const user = computed(function () {
        if (!!productPageUser) {
            return productPageUser;
        } else if (!!mainProfileUser) {
            return mainProfileUser;
        } else if (!!cartUser) {
            return cartUser;
        }
    });

    const isAuthenticated = computed(function () {
        if (!!mainIsAuthenticated) {
            return mainIsAuthenticated;
        } else if (!!productsIsAuthenticated) {
            return productsIsAuthenticated;
        } else if (!!productPageIsAuthenticated) {
            return productPageIsAuthenticated;
        } else if (!!contactUsIsAuthenticated) {
            return contactUsIsAuthenticated;
        } else if (!!aboutUsIsAuthenticated) {
            return aboutUsIsAuthenticated;
        } else if (!!categoryProductsIsAuthenticated) {
            return categoryProductsIsAuthenticated;
        } else if (!!tagProductsIsAuthenticated) {
            return tagProductsIsAuthenticated;
        } else if (!!checkoutIsAuthenticated) {
            return checkoutIsAuthenticated;
        } else if (!!mainProfileIsAuthenticated) {
            return mainProfileIsAuthenticated;
        } else if (!!cartIsAuthenticated) {
            return cartIsAuthenticated;
        } else if (!!shippingReturnsIsAuthenticated) {
            return shippingReturnsIsAuthenticated;
        } else if (!!privacyPolicyIsAuthenticated) {
            return privacyPolicyIsAuthenticated;
        } else if (!!termsIsAuthenticated) {
            return termsIsAuthenticated;
        } else if (!!notFoundIsAuthenticated) {
            return notFoundIsAuthenticated;
        }
    });

    return {
        user,
        isAuthenticated,
    };
}
