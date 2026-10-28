import { ref } from "vue";
import { http } from "../../utils/http";

const isVisibleDialog = ref(false);
const modal = ref({
    message: "",
    headerColor: "",
    buttonColor: "",
    titleText: "",
});
const isLoading = ref(false);
const message = ref(null);

export function useToken() {
    const submitToken = async (token) => {
        try {
            isLoading.value = true;
            const res = await http("/twofactor/phone", {
                method: "POST",
                body: JSON.stringify({
                    token: token,
                    phone: localStorage.getItem("phone"),
                }),
            });

            const data = await res.json();

            if (data.success && data.goToRoute == "twofactor") {
                isVisibleDialog.value = true;

                modal.value = {
                    message:
                        "شماره تلفن و احراز هویت دو مرحله ای شما تایید شد.",
                    headerColor: "var(--accent-color)",
                    buttonColor: "success",
                    titleText: "عملیات موفقیت آمیز",
                };
                setTimeout(() => {
                    window.location.href = "/profile/twofactor";
                }, 3000);
            }

            if (!data.success && data.error == "Not Exist Token") {
                message.value = "توکن صحیح نمی باشد.";
            }
            if (!data.success && data.error == "Expired Token") {
                message.value = "توکن منقضی شده است! از صفحه قبل دوباره تلاش کنید.";
            }
        } catch (error) {
            isVisibleDialog.value = true;
            modal.value = {
                message: "خطایی رخ داده است بعدا تلاش کنید." || error,
                headerColor: "var(--danger-color)",
                buttonColor: "danger",
                titleText: "عملیات ناموفق",
            };
        } finally {
            isLoading.value = false;
            setTimeout(() => {
                isVisibleDialog.value = false;
            }, 3000);
        }
    };

    return {
        isVisibleDialog,
        modal,
        isLoading,
        message,
        submitToken,
    };
}
