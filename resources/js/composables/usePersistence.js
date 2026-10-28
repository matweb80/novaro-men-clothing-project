// Storage access can throw a SecurityError inside a sandboxed about:srcdoc
// preview (e.g. an embedded artifact/iframe preview), so every call is
// guarded narrowly and falls back to plain in-memory state rather than
// hiding unrelated errors. Checked once at module load.
const STORAGE_AVAILABLE = (function () {
  try {
    const k = '__novaro_storage_test__'
    window.localStorage.setItem(k, '1')
    window.localStorage.removeItem(k)
    return true
  } catch (err) {
    return false
  }
})()

export function loadPersisted(key, fallback) {
  if (!STORAGE_AVAILABLE) return fallback
  try {
    const raw = window.localStorage.getItem(key)
    return raw ? JSON.parse(raw) : fallback
  } catch (err) {
    return fallback
  }
}

export function savePersisted(key, value) {
  if (!STORAGE_AVAILABLE) return
  try {
    window.localStorage.setItem(key, JSON.stringify(value))
  } catch (err) {
    // quota exceeded or blocked — state remains in-memory only
  }
}
