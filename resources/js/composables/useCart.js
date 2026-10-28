import { ref, computed, reactive } from "vue";
import { http } from "../utils/http";

const cart = reactive({
    items: [],
    totalCount: 0,
    count: 0,
    totalSpecialPrice: 0,
    totalDiscountPrice: 0,
    totalPrice: 0,
    oldTotalPrice: 0,
    loaded: false,
    attributeValues: false,
    discount: 0,
});

export function useCart() {
    const isLoading = ref(false);
    const isVisibleDialog = ref(false);
    const modal = ref({
        message: "",
        type: "",
    });

    const loadCart = async () => {
        try {
            isLoading.value = true;
            const res = await http(`/api/cart`);

            const data = await res.json();

            if (!res.ok) throw data;

            cart.items = data.cart;
            cart.totalCount = data.totalCount;
            cart.totalSpecialPrice = data.totalSpecialPrice;
            cart.totalDiscountPrice = data.totalDiscountPrice;
            cart.totalPrice = data.totalPrice;
            cart.oldTotalPrice = data.oldTotalPrice;
            cart.attributeValues = data.attributeValues;
            cart.discount = data.discount;
            cart.loaded = true;
        } catch (error) {
            console.error("Load Cart Failed");
        } finally {
            isLoading.value = false;
        }
    };

    const addToCart = async (product) => {
        try {
            isLoading.value = true;
            const res = await http(`/api/cart/add/${product.slug}`, {
                method: "POST",
            });

            const data = await res.json();

            if (!res.ok) throw data;

            if (data.success) {
                isVisibleDialog.value = true;

                cart.items = data.cart;
                cart.totalCount = data.totalCount;
                cart.totalSpecialPrice = data.totalSpecialPrice;
                cart.totalDiscountPrice = data.totalDiscountPrice;
                cart.totalPrice = data.totalPrice;
                cart.oldTotalPrice = data.oldTotalPrice;
                cart.count = data.count;
                cart.loaded = true;

                modal.value = {
                    message: "محصول به سبد خرید اضافه شد",
                    type: "success",
                };
            }
        } catch (error) {
            isVisibleDialog.value = true;
            modal.value = {
                message: "خطایی رخ داده است! لطفا دوباره تلاش کنید" || error,
                type: "danger",
            };
        } finally {
            isLoading.value = false;
            setTimeout(() => {
                isVisibleDialog.value = false;
            }, 5000);
        }
    };

    const changeQuantity = async (id, index, slug, cartName = "my-cart") => {
        try {
            isLoading.value = true;
            const res = await http(`/api/cart/quantity/change/${slug}`, {
                method: "PATCH",
                body: JSON.stringify({
                    id: id,
                    quantity: index,
                    cart: cartName,
                }),
            });

            const data = await res.json();

            if (!res.ok) throw data;

            if (data.status === "success") {
                cart.items = data.cart;
                cart.count = data.count;
                cart.totalCount = data.totalCount;
                cart.totalSpecialPrice = data.totalSpecialPrice;
                cart.totalDiscountPrice = data.totalDiscountPrice;
                cart.totalPrice = data.totalPrice;
                cart.oldTotalPrice = data.oldTotalPrice;
                cart.loaded = true;
            }
        } finally {
            isLoading.value = false;
        }
    };

    const removeFromCart = async (id) => {
        try {
            isLoading.value = true;
            const res = await http(`/api/cart/delete/${id}`, {
                method: "DELETE",
            });

            const data = await res.json();

            if (!res.ok) throw data;

            if (data.status === "success") {
                isVisibleDialog.value = true;

                cart.items = data.cart;
                cart.count = data.count;
                cart.totalCount = data.totalCount;
                cart.totalSpecialPrice = data.totalSpecialPrice;
                cart.totalDiscountPrice = data.totalDiscountPrice;
                cart.totalPrice = data.totalPrice;
                cart.oldTotalPrice = data.oldTotalPrice;
                cart.loaded = true;

                modal.value = {
                    message: "محصول از سبد خرید حذف شد",
                    type: "danger",
                };
            }
        } catch (error) {
            isVisibleDialog.value = true;
            modal.value = {
                message: "خطایی رخ داده است! لطفا دوباره تلاش کنید" || error,
                type: "danger",
            };
        } finally {
            isLoading.value = false;
            setTimeout(() => {
                isVisibleDialog.value = false;
            }, 5000);
        }
    };

    return {
        cart,
        isVisibleDialog,
        modal,
        isLoading,
        loadCart,
        addToCart,
        changeQuantity,
        removeFromCart,
    };
}
