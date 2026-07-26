# User Journey Map — SocialFeed

## 1. First Visit (Unauthenticated)

```
┌──────────┐    ┌──────────┐    ┌──────────┐
│ Landing   │───▶│ Register  │───▶│ Verify    │
│ Page      │    │ Form      │    │ Email     │
└──────────┘    └──────────┘    └──────────┘
     │
     ▼
┌──────────┐
│ Login     │
│ (existing)│
└──────────┘
```

**Pain points:**
- No guest preview of feed — user must register before seeing content
- No "continue as guest" option

**Fix:** Show 3-5 public posts on landing page to demonstrate value.

## 2. First Post (New User)

```
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│ Feed      │───▶│ Empty     │───▶│ Create    │───▶│ Post      │
│ (empty)   │    │ State CTA │    │ Post Box  │    │ Appears   │
└──────────┘    └──────────┘    └──────────┘    └──────────┘
```

**Pain points:**
- Empty state only shows when filter=friends AND no friends
- No onboarding tooltip or hint

**Fix:** 
- Show skeleton→empty state with "Jadilah yang pertama untuk membagikan status" CTA
- Highlight create post box with subtle pulse on first visit

## 3. Core Loop (Active User)

```
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│ Browse    │───▶│ Scroll    │───▶│ Interact  │───▶│ Repeat    │
│ Feed      │    │ (sentinel)│    │ (like/cmnt)│   │ (stays)   │
└──────────┘    └──────────┘    └──────────┘    └──────────┘
```

**Pain points:**
- No feedback when hitting end of feed (already fixed — end marker)
- Filter switch causes full re-render with flash

**Fix:**
- Add subtle transition when switching filters (already has stagger-enter)

## 4. Deep Engagement

```
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│ Feed      │───▶│ Profile   │───▶│ Follow/   │───▶│ Notif     │
│           │    │ Click     │    │ Friend    │    │ Center    │
└──────────┘    └──────────┘    └──────────┘    └──────────┘
     │                                             │
     ▼                                             ▼
┌──────────┐                              ┌──────────┐
│ Groups    │                              │ Messages  │
│ Page      │                              │           │
└──────────┘                              └──────────┘
```

## Key Flows by Page

| Page | Entry Point | Exit Point | Friction |
|------|-------------|------------|----------|
| Feed | Nav Feed / Login | Profile click, Search, Group click | Filter flash |
| Profile | Feed avatar click | Follow, Message, Back | No follow state feedback |
| Groups | Nav Groups / Left sidebar | Group detail, Create group | No create post shortcut in group |
| Messages | Nav Messages / Right sidebar | Conversation | No unread badge update on nav |
| Notifications | Nav Notifications | Feed (via notification link) | No mark-all-read button |

## Quick Wins

1. **Landing preview** — show 3 public posts before requiring login
2. **Filter transition** — add fade-out/fade-in between filter switches
3. **Follow button** — add loading state + success feedback on follow/unfollow
4. **Group create post** — add "Buat Postingan" button inside group detail
5. **Mark all read** — add button in notifications center
