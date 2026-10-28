import { ref } from 'vue'
import { useCart } from './useCart'

/**
 * Wraps the Add to Cart button's idle → loading → success timeline.
 * A missing required size is recoverable — status stays 'idle' and the
 * caller is told to focus the size selector, since a disabled button would
 * never fire the click that reveals that guidance in the first place. Out
 * of stock is a hard block, left to the caller (button disabled).
 */
export function useProductPurchase() {
  const { addToCart } = useCart()
  const status = ref('idle') // idle | loading | success

  function reducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches
  }

  function submit({ productId, qty, size, color, sizeRequired, onNeedsSize }) {
    if (status.value !== 'idle') return
    if (sizeRequired && !size) {
      onNeedsSize?.()
      return
    }
    status.value = 'loading'
    setTimeout(
      () => {
        addToCart(productId, qty, size, color)
        status.value = 'success'
        setTimeout(() => {
          status.value = 'idle'
        }, 1200)
      },
      reducedMotion() ? 0 : 350,
    )
  }

  return { status, submit }
}
