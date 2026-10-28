import { ref } from "vue";

export function useSliderProducts() {
    const products = ref([]);

    const isLoading = ref(false);

    // Get products
    const getProducts = async () => {
        try {
            isLoading.value = true;

            const res = await fetch(`/api/products`);
            const data = await res.json();

            products.value = Array.isArray(data.data) ? data.data : [];
        } catch (err) {
            console.error("خطا در دریافت محصولات:", err);
        } finally {
            isLoading.value = false;
        }
    };

    const getSpecialProducts = async () => {
        try {
            isLoading.value = true;

            const res = await fetch(`/api/special-products`);
            const data = await res.json();

            products.value = Array.isArray(data.data) ? data.data : [];
        } catch (err) {
            console.error("خطا در دریافت محصولات:", err);
        } finally {
            isLoading.value = false;
        }
    };

    return {
        products,
        isLoading,
        getProducts,
        getSpecialProducts
    };
}
