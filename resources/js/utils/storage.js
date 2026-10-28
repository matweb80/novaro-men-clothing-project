// resources/js/utils/storage.js
//
// هلپر مینیمال خواندن/نوشتن در localStorage، با همان الگوی محافظت‌شده‌ای که در
// NOVARO-architecture-audit.md (بخش ۵.۹) توضیح داده شده: فقط SecurityError
// (محیط sandbox مثل پیش‌نمایش Artifacts) گرفته می‌شود، بقیه‌ی خطاها دوباره
// throw می‌شوند — عمداً به یک catch کلی ساده نشده.
//
// اگر useLocalStorage()/loadPersisted/savePersisted در پروژه از قبل پیاده‌سازی
// شده (طبق نقشه‌ی §۱۳ audit، این‌ها باید foundation مشترک useCart/useWishlist
// هم باشند)، این فایل را حذف کن و importهای useProfile.js/useAddresses.js را
// به همان composable موجود سوییچ کن تا چیزی duplicate نشود.

function isStorageAvailable() {
  try {
    const testKey = '__novaro_storage_test__';
    window.localStorage.setItem(testKey, '1');
    window.localStorage.removeItem(testKey);
    return true;
  } catch (e) {
    if (e && e.name === 'SecurityError') return false;
    throw e;
  }
}

export const STORAGE_AVAILABLE = isStorageAvailable();

export function loadPersisted(key, fallback) {
  if (!STORAGE_AVAILABLE) return fallback;
  try {
    const raw = window.localStorage.getItem(key);
    return raw ? JSON.parse(raw) : fallback;
  } catch (e) {
    if (e && e.name === 'SecurityError') return fallback;
    throw e;
  }
}

export function savePersisted(key, value) {
  if (!STORAGE_AVAILABLE) return;
  try {
    window.localStorage.setItem(key, JSON.stringify(value));
  } catch (e) {
    if (e && e.name === 'SecurityError') return;
    throw e;
  }
}
