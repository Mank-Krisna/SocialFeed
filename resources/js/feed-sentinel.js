// Feed infinite scroll sentinel observer
// Falls back to "Load more" button if IntersectionObserver unavailable

document.addEventListener('DOMContentLoaded', () => {
  const sentinel = document.getElementById('feed-sentinel')
  const hasMore = document.getElementById('feed-has-more') // hidden flag from Livewire

  if (!sentinel) return

  if ('IntersectionObserver' in window) {
    let loading = false
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && !loading && window.Livewire) {
            loading = true
            entry.target.dispatchEvent(new CustomEvent('feed-load-more'))
            setTimeout(() => { loading = false }, 500)
          }
        })
      },
      { rootMargin: '0px 0px 200px 0px', threshold: 0 }
    )
    observer.observe(sentinel)
  }
})
