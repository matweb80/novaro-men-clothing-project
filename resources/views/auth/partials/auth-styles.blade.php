<style>
    .auth-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: var(--sp-8);
        padding: var(--sp-8) var(--sp-4);
        background: var(--c-bg);
    }

    .auth-brand {
        font-size: 20px;
        font-weight: 700;
        letter-spacing: .08em;
        color: var(--c-navy);
        text-decoration: none;
    }

    .auth-card {
        width: 100%;
        max-width: 400px;
        background: var(--c-white);
        border: 1px solid var(--c-border);
        border-radius: var(--r-md);
        padding: var(--sp-8);
    }

    .auth-card__title {
        font-size: var(--fs-h3);
        font-weight: 500;
        margin-block: var(--sp-1) var(--sp-6);
    }

    .auth-card__footer {
        text-align: center;
        font-size: var(--fs-small);
        color: var(--c-text-muted);
        margin-top: var(--sp-6);
    }

    .auth-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-block: var(--sp-2) var(--sp-6);
    }

    .auth-link {
        font-size: var(--fs-small);
        color: var(--c-navy);
        text-decoration: underline;
    }

    .auth-divider {
        display: flex;
        align-items: center;
        gap: var(--sp-3);
        margin-block: var(--sp-6);
        color: var(--c-text-muted);
        font-size: var(--fs-caption);
    }

    .auth-divider::before,
    .auth-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: var(--c-border);
    }

    .auth-social {
        display: flex;
        flex-direction: column;
        gap: var(--sp-3);
    }

    .auth-social__btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--sp-3);
        border: 1px solid var(--c-border-strong);
        border-radius: var(--r-sm);
        padding: var(--sp-3) var(--sp-4);
        font-size: var(--fs-small);
        color: var(--c-text);
        text-decoration: none;
        transition: border-color var(--dur-fast) var(--ease);
    }

    .auth-social__btn:hover {
        border-color: var(--c-navy);
    }

    .auth-social__btn svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .auth-footer-note {
        font-size: var(--fs-caption);
        color: var(--c-text-muted);
    }

    /* الگوی فرم — عیناً همان کلاس‌های form-group/form-input/form-error که در
   Vue pages (Contact/Profile/...) تکرار شده بود؛ این‌جا چون Blade خام
   است (نه Vue scoped)، باز هم محلی تعریف شد. اگر این کلاس‌ها را به یک
   stylesheet سراسری منتقل کردی، این بلوک را حذف کن. */
    .form-group {
        display: flex;
        flex-direction: column;
        gap: var(--sp-2);
        margin-bottom: var(--sp-5);
    }

    .form-label {
        font-size: var(--fs-small);
        font-weight: 600;
        color: var(--c-text);
    }

    .form-input {
        width: 100%;
        padding: var(--sp-3) var(--sp-4);
        border: 1px solid var(--c-border-strong);
        border-radius: var(--r-sm);
        background: var(--c-white);
        color: var(--c-text);
        font-size: var(--fs-body);
        font-family: var(--font);
    }

    .form-input:focus {
        border-color: var(--c-navy);
    }

    .form-input[aria-invalid="true"] {
        border-color: var(--c-danger);
    }

    .form-error {
        font-size: var(--fs-small);
        color: var(--c-danger);
        margin-top: var(--sp-1);
    }

    .form-success {
        font-size: var(--fs-small);
        color: var(--c-success);
        margin-bottom: var(--sp-4);
    }

    .filter-check {
        display: flex;
        align-items: center;
        gap: var(--sp-2);
        font-size: var(--fs-small);
        cursor: pointer;
    }
</style>