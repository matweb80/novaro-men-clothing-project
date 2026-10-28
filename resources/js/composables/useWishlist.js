import { reactive } from 'vue'
import { loadPersisted, savePersisted } from './usePersistence'

// Module-scoped, persisted reactive state, shared across every ProductCard
// instance — the same role `new Set(loadPersisted(...))` played in the
// prototype.
const wishlist = reactive(new Set(loadPersisted('novaro:wishlist', [])))

function persist() {
  savePersisted('novaro:wishlist', Array.from(wishlist))
}

export function useWishlist() {
  function has(id) {
    return wishlist.has(id)
  }

  function toggle(id) {
    if (wishlist.has(id)) wishlist.delete(id)
    else wishlist.add(id)
    persist()
  }

  return { wishlist, has, toggle }
}
