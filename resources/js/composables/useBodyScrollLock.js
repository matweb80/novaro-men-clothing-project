/** Lock/unlock body scroll — used by any dialog-like overlay (mobile nav, search, cart). */
export function useBodyScrollLock() {
  function lock() {
    document.body.style.overflow = 'hidden'
  }
  function unlock() {
    document.body.style.overflow = ''
  }
  return { lock, unlock }
}
