import { ref } from "vue";
import { http } from "../../utils/http";

const isVisibleDialog = ref(false);
const modal = ref({
    message: "",
    headerColor: "",
    buttonColor: "",
    titleText: "",
});
const errors = ref({});
const isLoading = ref(null);
const infoSaving = ref(null);
const infoSaved = ref(null);

const userName = ref(null);
const userEmail = ref(null);
const userPhoneNumber = ref(null);

export function useEditAccount() {
    const fetchUser = async () => {
        try {
            isLoading.value = true;

            const res = await http("/user");

            const data = await res.json();

            if (res.ok) {
                userName.value = data.user_name;
                userEmail.value = data.user_email;
                userPhoneNumber.value = data.user_phone_number;
            }
        } catch (error) {
            console.error("Failed to fetch user: ", error);
        } finally {
            isLoading.value = false;
        }
    };
    const validateForm = (
        name,
        email,
        phone,
        password,
        password_confirmation,
    ) => {
        errors.value = {};

        if (!name) {
            errors.value.name = "نام کاربری الزامی است";
        }
        if (!email) {
            errors.value.email = "ایمیل الزامی است";
        }
        if (!!phone && !/^09\d{9}$/.test(phone)) {
            errors.value.phone = "شماره موبایل معتبر نیست";
        }
        if (!!password && password.length < 8) {
            errors.value.password = "رمز عبور کمتر از 8 کاراکتر است";
        }
        if (
            !!password &&
            !!password_confirmation &&
            password !== password_confirmation
        ) {
            errors.value.password = "رمز عبور و تکرار آن یکسان نیستند";
        }
        if (!!password && !password_confirmation) {
            errors.value.password_confirmation = "تکرار رمز عبور را وارد کنید";
        }

        return Object.keys(errors.value).length === 0;
    };
    const submitEditAccount = async (
        name,
        email,
        phone,
        password,
        password_confirmation,
    ) => {
        if (!validateForm(name, email, phone, password, password_confirmation))
            return;
        try {
            infoSaving.value = true;

            const res = await http("/edit-account", {
                method: "POST",
                body: JSON.stringify({
                    name: name,
                    email: email,
                    phone: phone,
                    password: password,
                    password_confirmation: password_confirmation,
                }),
            });

            const data = await res.json();

            if (data.success && data.goToRoute == "edit-account") {
                infoSaving.value = false;
                infoSaved.value = true;
            }
        } catch (err) {
            infoSaving.value = false;
        } finally {
            infoSaving.value = false;
            setTimeout(() => {
                infoSaved.value = false;
            }, 5000);
        }
    };

    return {
        errors,
        isLoading,
        validateForm,
        submitEditAccount,
        infoSaving,
        infoSaved,
        fetchUser,
        userName,
        userEmail,
        userPhoneNumber,
    };
}
