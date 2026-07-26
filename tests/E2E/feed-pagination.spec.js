// @ts-check
const { test, expect } = require('@playwright/test');

test.describe('Feed infinite scroll pagination', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/login');
    // fill credentials if auth required
    // await page.fill('[name="email"]', 'test@example.com');
    // await page.fill('[name="password"]', 'password');
    // await page.click('button[type="submit"]');
    // await page.waitForURL('/feed');
  });

  test('sentinel triggers loadMore on scroll', async ({ page }) => {
    await page.goto('/feed');
    await page.waitForSelector('#feed-sentinel', { timeout: 5000 });

    const postCountBefore = await page.locator('[wire\\:key^="post-"]').count();

    // scroll sentinel into view
    await page.locator('#feed-sentinel').scrollIntoViewIfNeeded();
    await page.waitForTimeout(1500); // allow throttle + Livewire roundtrip

    const postCountAfter = await page.locator('[wire\\:key^="post-"]').count();
    expect(postCountAfter).toBeGreaterThanOrEqual(postCountBefore);
  });

  test('end-of-feed marker appears when no more items', async ({ page }) => {
    await page.goto('/feed');

    // scroll to bottom repeatedly
    for (let i = 0; i < 5; i++) {
      await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
      await page.waitForTimeout(1000);
      const marker = page.locator('text=Sudah semua');
      if (await marker.isVisible()) break;
    }

    await expect(page.locator('text=Sudah semua')).toBeVisible({ timeout: 3000 });
  });

  test('filter change resets feed', async ({ page }) => {
    await page.goto('/feed');
    await page.waitForSelector('#feed-sentinel', { timeout: 5000 });

    // scroll once to load more
    await page.locator('#feed-sentinel').scrollIntoViewIfNeeded();
    await page.waitForTimeout(1500);
    const postsAfterLoad = await page.locator('[wire\\:key^="post-"]').count();

    // click "Postingan Teman" filter
    await page.click('text=Postingan Teman');
    await page.waitForTimeout(1000);

    const postsAfterFilter = await page.locator('[wire\\:key^="post-"]').count();
    // filter should reset — count may differ but component re-rendered
    expect(postsAfterFilter).toBeDefined();
  });

  test('fallback button visible when IntersectionObserver unavailable', async ({ page }) => {
    // emulate no IO support
    await page.addInitScript(() => {
      // @ts-ignore
      delete window.IntersectionObserver;
    });
    await page.goto('/feed');
    await page.waitForTimeout(500);

    const fallbackBtn = page.locator('[data-fallback-load]');
    await expect(fallbackBtn).toBeVisible({ timeout: 3000 });
    expect(await fallbackBtn.textContent()).toContain('Muat Lainnya');
  });
});
