// خواندن کوکی XSRF-TOKEN
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(";").shift();
    return null;
}

// گرفتن CSRF از سرور (فقط لازم باشد)
async function ensureCsrfCookie() {
    const token = getCookie("XSRF-TOKEN");

    if (!token) {
        // گرفتن CSRF جدید
        await fetch("/sanctum/csrf-cookie", {
            method: "GET",
            credentials: "include",
        });
    }
}

// wrapper برای fetch
export async function http(url, options = {}) {
    // اگر متد مشخص نشده → GET
    const method = (options.method || "GET").toUpperCase();

    // اگر درخواست تغییر حالت است → CSRF لازم است
    if (["POST", "PUT", "PATCH", "DELETE"].includes(method)) {
        await ensureCsrfCookie();
        const csrf = decodeURIComponent(getCookie("XSRF-TOKEN"));

        // اضافه کردن هدر CSRF
        options.headers = {
            ...options.headers,
            "X-XSRF-TOKEN": csrf,
            "Content-Type": "application/json",
            Accept: "application/json",
        };
    }

    // احراز هویت از طریق کوکی
    options.credentials = "include";

    // ارسال درخواست
    let res = await fetch(url, options);

    // اگر 419 (token mismatch) → CSRF را رفرش و درخواست را تکرار کن
    if (res.status === 419) {
        await ensureCsrfCookie();
        const csrf = decodeURIComponent(getCookie("XSRF-TOKEN"));

        options.headers["X-XSRF-TOKEN"] = csrf;

        res = await fetch(url, options);
    }

    if (!res.ok) {
        const error = await res.catch(() => ({}));
        throw {
            status: res.status,
            data: error,
        };
    }

    return res;
}
