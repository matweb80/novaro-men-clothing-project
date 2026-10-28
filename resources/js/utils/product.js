import { CATEGORY_TREE } from '@/data/categories'
import { findCategoryById, getDescendantIds } from './category'

/** Products belonging to `categoryId` or any of its descendant categories. */
export function getProductsForCategory(products, categoryId) {
  const node = findCategoryById(CATEGORY_TREE, categoryId)
  if (!node) return []
  const ids = new Set(getDescendantIds(node))
  return products.filter((p) => ids.has(p.categoryId))
}

/** Apply the Shop page's size/color/price/stock/discount filters. */
export function filterProducts(products, filters) {
  return products.filter((p) => {
    if (filters.sizes.size && !p.sizes.some((s) => filters.sizes.has(s))) return false
    if (filters.colors.size && !filters.colors.has(p.color)) return false
    if (filters.max_price != null && p.price > filters.max_price) return false
    if (filters.in_stock_only && p.inventory === 0) return false
    if (filters.special_price && !p.special_price) return false
    return true
  })
}

function discountRatio(p) {
  return p.originalPrice ? 1 - p.price / p.originalPrice : 0
}

/** Sort a product list by one of SORT_OPTIONS' ids. Never mutates input. */
export function sortProducts(products, sort) {
  const out = products.slice()
  switch (sort) {
    case 'cheapest':
      return out.sort((a, b) => a.price - b.price)
    case 'expensive':
      return out.sort((a, b) => b.price - a.price)
    case 'discount':
      return out.sort((a, b) => discountRatio(b) - discountRatio(a))
    case 'bestselling':
      return out.sort((a, b) => a.popularity - b.popularity)
    case 'newest':
    default:
      return out.reverse()
  }
}
