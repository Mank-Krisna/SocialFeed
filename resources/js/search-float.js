// Floating search bar
// Shows compact search when nav scrolls out of view

document.addEventListener('DOMContentLoaded', () => {
  const sentinel = document.getElementById('header-sentinel')
  const floating = document.getElementById('floating-search')

  if (!sentinel || !floating) return

  function show() {
    floating.classList.remove('hidden')
  }

  function hide() {
    floating.classList.add('hidden')
  }

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      ([entry]) => {
        entry.isIntersecting ? hide() : show()
      },
      { threshold: 0 }
    )
    observer.observe(sentinel)
  } else {
    // Fallback: scroll event
    let ticking = false
    const threshold = 64
    window.addEventListener(
      'scroll',
      () => {
        if (!ticking) {
          requestAnimationFrame(() => {
            window.scrollY > threshold ? show() : hide()
            ticking = false
          })
          ticking = true
        }
      },
      { passive: true }
    )
  }
})
