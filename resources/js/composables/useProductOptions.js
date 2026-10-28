import { ref, computed, watch } from 'vue'

export function useProductOptions(product) {
  const size = ref(null)
  const color = ref(null)
  const qty = ref(1)

  // Navigating to a different product (id changes) starts selection fresh —
  // the same effect a full page load had in the prototype, since it never
  // carried size/color/qty across products.
  watch(
    () => product.value?.id,
    () => {
      size.value = null
      color.value = null
      qty.value = 1
    },
  )

  const maxQty = computed(() => Math.min(product.value?.stock ?? 1, 10))
  const canDecrement = computed(() => qty.value > 1)
  const canIncrement = computed(() => qty.value < maxQty.value)

  function selectSize(s) {
    size.value = s
  }
  function selectColor(c) {
    color.value = c
  }
  function increment() {
    if (canIncrement.value) qty.value++
  }
  function decrement() {
    if (canDecrement.value) qty.value--
  }

  return { size, color, qty, maxQty, canDecrement, canIncrement, selectSize, selectColor, increment, decrement }
}
