const FOCUSABLE_SELECTOR = 'a[href],button:not([disabled]),input,[tabindex]:not([tabindex="-1"])'

/**
 * Simple focus trap for dialogs (mobile nav, search overlay, cart drawer).
 * trap(container) attaches a keydown handler and returns a release() fn.
 */
export function useFocusTrap() {
  let release = null

  function trap(container) {
    if (!container) return
    const focusables = Array.from(container.querySelectorAll(FOCUSABLE_SELECTOR)).filter(
      (elx) => elx.offsetParent !== null,
    )
    if (!focusables.length) return

    const first = focusables[0]
    const last = focusables[focusables.length - 1]

    function handler(e) {
      if (e.key !== 'Tab') return
      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault()
        last.focus()
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault()
        first.focus()
      }
    }

    container.addEventListener('keydown', handler)
    release = () => container.removeEventListener('keydown', handler)
  }

  function releaseTrap() {
    if (release) {
      release()
      release = null
    }
  }

  return { trap, releaseTrap }
}
