import { ref } from "vue";
import { http } from "../../utils/http";

const error = ref({});
const twoFactorLoading = ref(false);
const message = ref(null);

export function useTwoFactor() {
    const validateForm = (phone) => {
        error.value = {};

        if (!phone) {
            error.value.phone = "شماره موبایل الزامی است";
        } else if (!/^09\d{9}$/.test(phone)) {
            error.value.phone = "شماره موبایل معتبر نیست";
        }

        return Object.keys(error.value).length === 0;
    };
    const submitTwoFactor = async (phone, twoFactorType) => {
        if (!validateForm(phone)) return;
        try {
            twoFactorLoading.value = true;
            const res = await http("/twofactor", {
                method: "POST",
                body: JSON.stringify({
                    type: twoFactorType,
                    phone: phone,
                }),
            });

            const data = await res.json();

            if (data.success && data.goToRoute == "twofactor/phone") {
                window.location.href = "/profile/twofactor/phone";
                localStorage.setItem("phone", data.phone);
            }

            if (data.success && data.goToRoute == "twofactor") {
                message.value =
                    "احراز هویت دو مرحله ای برای این شماره موبایل قبلا انجام شده است!";
            }
        } catch (err) {
            error.value = err;
        } finally {
            twoFactorLoading.value = false;
            setTimeout(() => {
                message.value = null;
            }, 8000);
        }
    };

    return {
        error,
        message,
        twoFactorLoading,
        submitTwoFactor,
        validateForm,
    };
}
