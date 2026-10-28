import { ref } from "vue";
import { http } from "../utils/http";

const cartDiscount = ref(null);
const error = ref(null);

export function useDiscount() {
    const isLoading = ref(false);
    const isVisibleDialog = ref(false);
    const modal = ref({
        message: "",
        type: "",
    });

    const checkDiscount = async (discount, cartName = "my-cart") => {
        try {
            isLoading.value = true;
            const res = await http(`/discount/check`, {
                method: "POST",
                body: JSON.stringify({
                    discount: discount,
                    cart: cartName,
                }),
            });

            const data = await res.json();

            if (!res.ok) throw data;

            if (!data.success && data.message == "Login Attempt") {
                error.value = "برای اعمال کد تخفیف لطفا ابتدا وارد سایت شوید";
            }

            if (!data.success && data.message == "Expired Discount") {
                error.value = "مهلت استفاده از این کد تخفیف به پایان رسیده است";
            }

            if (!data.success && data.message == "Unaccessable Discount") {
                error.value = "شما قادر به استفاده از این کد تخفیف نیستید";
            }

            if (data.success) {
                isVisibleDialog.value = true;

                modal.value = {
                    message: "کد تخفیف اضافه شد",
                    type: "success",
                };

                cartDiscount.value = data.cart;
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

    const deleteDiscount = async (cartName = "my-cart") => {
        try {
            isLoading.value = true;
            const res = await http(`/discount/delete`, {
                method: "DELETE",
                body: JSON.stringify({
                    cart: cartName,
                }),
            });

            const data = await res.json();

            if (!res.ok) throw data;

            if (data.success) {
                isVisibleDialog.value = true;

                modal.value = {
                    message: "کد تخفیف حذف شد",
                    type: "success",
                };

                cartDiscount.value = null;
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
        cartDiscount,
        error,
        checkDiscount,
        deleteDiscount,
        isLoading,
        isVisibleDialog,
        modal,
    };
}
