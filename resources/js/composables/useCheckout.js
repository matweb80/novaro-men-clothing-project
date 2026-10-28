// resources/js/composables/useCheckout.js
//
// فقط routeهایی که در web.php واقعی تأیید شدند استفاده شده‌اند:
//   GET  /user               → UserDataController@user      (Profile APIs)
//   GET  /checkout/address   → AddressController@show       (middleware auth)
//   POST /checkout/address   → AddressController@store      (middleware auth)
// پرداخت (POST /payment) عمداً این‌جا نیست؛ در CheckoutPage.vue با فرم
// native ارسال می‌شود (توضیحش همان‌جا آمده).
//
// ⚠️ کنترلرها (AddressController/UserDataController) برایم ارسال نشده‌اند؛
// پس شکل دقیق پاسخ و نام فیلدهای POST حدس است و صریحاً normalize شده:
//   - پاسخ آدرس ممکن است object یا آرایه یا { data: ... } باشد
//   - نام فیلدهای ارسالی (name, mobile, province, city, address,
//     postal_code) باید با validation واقعی AddressController یکی شوند
// خطاهای 422 استاندارد Laravel ({ errors: { field: [msg] } }) به فیلدها
// map می‌شوند — این بخش واقعاً استاندارد است، نه حدس.
import { computed, ref } from "vue";
import { http } from "@/utils/http";

const form = ref({
    billing_full_name: "",
    billing_mobile: "",
    province_id: "",
    city_id: "",
    billing_address: "",
    billing_postal_code: "",
    billing_extra_description: "",
});

const originalAddress = ref(null);
const userAddressLoaded = ref(false);

function pickFirst(data) {
    if (Array.isArray(data)) return data[0] ?? null;
    if (data && typeof data === "object" && "data" in data)
        return pickFirst(data.data);
    return data ?? null;
}

export function useCheckout() {
    const fieldErrors = ref({});
    const formError = ref("");
    const saving = ref(false);

    const loadAddress = async () => {
        const res = await http("/checkout/address");
        const data = await res.json();

        if (data.address) {
            form.value = { ...data.address };
            originalAddress.value = JSON.stringify(data.address);
            userAddressLoaded.value = true;
        }
    };

    const isChanged = computed(() => {
        return JSON.stringify(form.value) !== originalAddress.value;
    });

    async function saveAddress() {
        fieldErrors.value = {};
        formError.value = "";
        saving.value = true;
        try {
            let address_id;
            if (isChanged.value) {
                const addressRes = await http("/checkout/address", {
                    method: "POST",
                    body: JSON.stringify(form.value),
                });

                const addressData = await addressRes.json();
                address_id = addressData.address_id;

                const paymentRes = await http("/payment", {
                    method: "POST",
                    body: JSON.stringify({ address_id }),
                });
                const paymentData = await paymentRes.json();
                if (!paymentData.success && paymentData.error == "Cart empty") {
                    isVisibleDialog.value = true;

                    modal.value = {
                        message: "سبد خرید شما خالی است!",
                        type: "danger",
                    };
                }
                if (res.status === 422) {
                    const data = await res.json().catch(() => ({}));
                    const mapped = {};
                    for (const [field, msgs] of Object.entries(
                        data.errors ?? {},
                    ))
                        mapped[field] = msgs[0];
                    fieldErrors.value = mapped;
                    formError.value = "لطفاً خطاهای فرم آدرس را اصلاح کنید.";
                    return false;
                }
                if (!res.ok) {
                    formError.value =
                        "ثبت آدرس با خطا مواجه شد. دوباره تلاش کنید.";
                    return false;
                }
                return true;
            } else {
                const res = await http("/checkout/address");
                const data = await res.json();
                address_id = data.address.id;

                const paymentRes = await http("/payment", {
                    method: "POST",
                    body: JSON.stringify({ address_id }),
                });
                const paymentData = await paymentRes.json();
                if (!paymentData.success && paymentData.error == "Cart empty") {
                    isVisibleDialog.value = true;

                    modal.value = {
                        message: "سبد خرید شما خالی است!",
                        type: "danger",
                    };
                }
                if (res.status === 422) {
                    const data = await res.json().catch(() => ({}));
                    const mapped = {};
                    for (const [field, msgs] of Object.entries(
                        data.errors ?? {},
                    ))
                        mapped[field] = msgs[0];
                    fieldErrors.value = mapped;
                    formError.value = "لطفاً خطاهای فرم آدرس را اصلاح کنید.";
                    return false;
                }
                if (!res.ok) {
                    formError.value =
                        "ثبت آدرس با خطا مواجه شد. دوباره تلاش کنید.";
                    return false;
                }
                return true;
            }
        } catch (e) {
            formError.value = "خطا در ارتباط با سرور. دوباره تلاش کنید.";
            return false;
        } finally {
            saving.value = false;
        }
    }

    return {
        form,
        originalAddress,
        userAddressLoaded,
        fieldErrors,
        formError,
        saving,
        loadAddress,
        saveAddress,
    };
}
