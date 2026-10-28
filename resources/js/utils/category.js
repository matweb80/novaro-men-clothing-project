/**
 * Flatten a recursive category tree into a list of
 * { id, name, path } where `path` is the "Parent / Child / …" breadcrumb.
 * Used by search to match categories at any depth.
 */
export function flattenCategories(tree, path = []) {
  let out = []
  tree.forEach((node) => {
    const fullPath = path.concat([node.name])
    out.push({ id: node.id, name: node.name, path: fullPath.join(' / ') })
    if (node.child && node.child.length) {
      out = out.concat(flattenCategories(node.child, fullPath))
    }
  })
  return out
}

/** Depth-first search for a category node by id anywhere in the tree. */
export function findCategoryById(tree, id) {
  for (const node of tree) {
    if (node.id === id) return node
    if (node.children) {
      const found = findCategoryById(node.children, id)
      if (found) return found
    }
  }
  return null
}

export function findCategoryBySlug(tree, slug) {
  for (const node of tree) {
    if (node.slug === slug) return node
    if (node.child) {
      const found = findCategoryBySlug(node.child, slug)
      if (found) return found
    }
  }
  return null
}

/**
 * Returns the ancestor chain from root to `id` (inclusive), for breadcrumbs.
 * Works at any depth — nothing here assumes a fixed number of levels.
 */
export function findCategoryPath(tree, id, trail = []) {
  for (const node of tree) {
    const nextTrail = trail.concat([node])
    if (node.id === id) return nextTrail
    if (node.children) {
      const found = findCategoryPath(node.children, id, nextTrail)
      if (found) return found
    }
  }
  return null
}

/**
 * Every category id in the subtree rooted at `node`, including itself —
 * used so viewing a parent category also surfaces its descendants' products.
 */
export function getDescendantIds(node) {
  let out = [node.id]
  if (node.children) {
    node.children.forEach((child) => {
      out = out.concat(getDescendantIds(child))
    })
  }
  return out
}
