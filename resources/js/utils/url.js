/** router-link :to target for a given category id on the Shop page. */
export function shopCategoryLink(categoryId) {
  return { path: '/products', query: { cat: categoryId } }
}
