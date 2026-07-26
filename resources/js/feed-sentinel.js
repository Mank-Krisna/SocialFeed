// Feed infinite scroll sentinel
// Primary: IntersectionObserver dispatching feed:loadMore on window
// Fallback: scroll listener + exposed showFallbackButton()

;(function () {
  'use strict'

  const SENTINEL_ID = 'feed-sentinel'
  const THROTTLE_MS = 400
  const SCROLL_MARGIN = 300

  let lastTrigger = 0

  function dispatchLoadMore() {
    const now = Date.now()
    if (now - lastTrigger < THROTTLE_MS) return
    lastTrigger = now
    window.dispatchEvent(new CustomEvent('feed:loadMore'))
  }

  function initObserver() {
    const el = document.getElementById(SENTINEL_ID)
    if (!el) return false

    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) dispatchLoadMore()
          })
        },
        { rootMargin: `0px 0px ${SCROLL_MARGIN}px 0px`, threshold: 0 }
      )
      observer.observe(el)
      return true
    }
    return false
  }

  function initScrollFallback() {
    const el = document.getElementById(SENTINEL_ID)
    if (!el) return

    window.addEventListener('scroll', () => {
      if (!el.isConnected) return
      const rect = el.getBoundingClientRect()
      if (rect.top - window.innerHeight < SCROLL_MARGIN) dispatchLoadMore()
    }, { passive: true })
  }

  function showFallbackButton() {
    const el = document.getElementById(SENTINEL_ID)
    if (!el) return
    let btn = el.querySelector('[data-fallback-load]')
    if (btn) return
    btn = document.createElement('button')
    btn.dataset.fallbackLoad = ''
    btn.textContent = 'Muat Lainnya'
    btn.className = 'px-4 py-2 bg-gray-200 hover:bg-gray-300 text-sm rounded-full transition'
    btn.addEventListener('click', dispatchLoadMore)
    el.appendChild(btn)
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      if (!initObserver()) {
        initScrollFallback()
        showFallbackButton()
      }
    })
  } else {
    if (!initObserver()) {
      initScrollFallback()
      showFallbackButton()
    }
  }

  window.__feedSentinel = { dispatchLoadMore, showFallbackButton }
})()
