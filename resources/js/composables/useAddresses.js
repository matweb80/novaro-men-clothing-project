// resources/js/composables/useAddresses.js
//
// برخلاف useProfile، این بخش می‌تواند همین حالا هم بدون بک‌اند کاملاً کار کند
// (دقیقاً مثل cart/wishlist در پروتوتایپ) — فقط برای persist شدن بین کاربران
// مختلف/دستگاه‌های مختلف در آینده به API نیاز دارد.
//
// TODO(backend): وقتی API آماده شد، addAddress/updateAddress/removeAddress باید
// به‌ترتیب POST/PUT/DELETE /api/addresses را صدا بزنند؛ شکل داده همین الان هم
// API-ready است.
import { reactive } from 'vue';
import { loadPersisted, savePersisted } from '../utils/storage.js';

const STORAGE_KEY = 'novaro:addresses';

function persist(list) {
  savePersisted(STORAGE_KEY, list);
}

function makeId() {
  return 'addr_' + Math.random().toString(36).slice(2, 10);
}

export function useAddresses() {
  const addresses = reactive(loadPersisted(STORAGE_KEY, []));

  function addAddress(data) {
    const address = { id: makeId(), isDefault: addresses.length === 0, ...data };
    addresses.push(address);
    persist(addresses);
    return address;
  }

  function updateAddress(id, patch) {
    const target = addresses.find((a) => a.id === id);
    if (!target) return;
    Object.assign(target, patch);
    persist(addresses);
  }

  function removeAddress(id) {
    const index = addresses.findIndex((a) => a.id === id);
    if (index === -1) return;
    const wasDefault = addresses[index].isDefault;
    addresses.splice(index, 1);
    if (wasDefault && addresses[0]) addresses[0].isDefault = true;
    persist(addresses);
  }

  function setDefaultAddress(id) {
    addresses.forEach((a) => {
      a.isDefault = a.id === id;
    });
    persist(addresses);
  }

  return { addresses, addAddress, updateAddress, removeAddress, setDefaultAddress };
}
