import { ref } from 'vue'

/**
 * One scroll-snap stage drives both mobile swipe and desktop click
 * navigation, and the lightbox shares the same activeImage index — so
 * there is exactly one gallery mechanism, not two, matching the
 * prototype's single pdpState.activeImage.
 */
export function useProductGallery(imageCount) {
  const activeImage = ref(0)
  const stageEl = ref(null)

  const lightboxOpen = ref(false)
  const lightboxTriggerEl = ref(null)

  function reducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches
  }

  function goToImage(index) {
    activeImage.value = Math.max(0, Math.min(imageCount - 1, index))
    const stage = stageEl.value
    const slide = stage?.children?.[activeImage.value]
    slide?.scrollIntoView({
      behavior: reducedMotion() ? 'auto' : 'smooth',
      inline: 'start',
      block: 'nearest',
    })
  }

  function next() {
    goToImage(activeImage.value + 1)
  }
  function prev() {
    goToImage(activeImage.value - 1)
  }

  function onStageKeydown(e) {
    if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') {
      e.preventDefault()
      next()
    }
    if (e.key === 'ArrowRight' || e.key === 'ArrowUp') {
      e.preventDefault()
      prev()
    }
  }

  function openLightbox(index, triggerEl) {
    activeImage.value = index
    lightboxTriggerEl.value = triggerEl || null
    lightboxOpen.value = true
  }

  function closeLightbox() {
    lightboxOpen.value = false
    goToImage(activeImage.value)
  }

  function lightboxStep(delta) {
    activeImage.value = Math.max(0, Math.min(imageCount - 1, activeImage.value + delta))
  }

  function onLightboxKeydown(e) {
    if (e.key === 'Escape') closeLightbox()
    if (e.key === 'ArrowLeft') lightboxStep(1)
    if (e.key === 'ArrowRight') lightboxStep(-1)
  }

  return {
    activeImage,
    stageEl,
    goToImage,
    next,
    prev,
    onStageKeydown,
    lightboxOpen,
    lightboxTriggerEl,
    openLightbox,
    closeLightbox,
    lightboxStep,
    onLightboxKeydown,
  }
}
