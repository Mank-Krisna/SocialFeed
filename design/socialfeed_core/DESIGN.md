---
name: SocialFeed Core
colors:
  surface: '#f9f9fd'
  surface-dim: '#d9dade'
  surface-bright: '#f9f9fd'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f3f7'
  surface-container: '#ededf1'
  surface-container-high: '#e8e8ec'
  surface-container-highest: '#e2e2e6'
  on-surface: '#1a1c1f'
  on-surface-variant: '#414754'
  inverse-surface: '#2f3034'
  inverse-on-surface: '#f0f0f4'
  outline: '#727785'
  outline-variant: '#c1c6d6'
  surface-tint: '#005bc0'
  primary: '#0058bc'
  on-primary: '#ffffff'
  primary-container: '#0070eb'
  on-primary-container: '#fefcff'
  inverse-primary: '#adc6ff'
  secondary: '#5c5e62'
  on-secondary: '#ffffff'
  secondary-container: '#e1e2e7'
  on-secondary-container: '#626468'
  tertiary: '#525d67'
  on-tertiary: '#ffffff'
  tertiary-container: '#6a7680'
  on-tertiary-container: '#fcfcff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d8e2ff'
  primary-fixed-dim: '#adc6ff'
  on-primary-fixed: '#001a41'
  on-primary-fixed-variant: '#004493'
  secondary-fixed: '#e1e2e7'
  secondary-fixed-dim: '#c5c6cb'
  on-secondary-fixed: '#191c1f'
  on-secondary-fixed-variant: '#45474b'
  tertiary-fixed: '#d8e4f0'
  tertiary-fixed-dim: '#bcc8d3'
  on-tertiary-fixed: '#111d25'
  on-tertiary-fixed-variant: '#3d4852'
  background: '#f9f9fd'
  on-background: '#1a1c1f'
  surface-variant: '#e2e2e6'
typography:
  headline-lg:
    fontFamily: Inter
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 34px
    letterSpacing: -0.02em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 22px
    fontWeight: '700'
    lineHeight: 28px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 26px
  body-lg:
    fontFamily: Inter
    fontSize: 17px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Inter
    fontSize: 15px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Inter
    fontSize: 13px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.01em
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 4px
  space-xs: 4px
  space-sm: 8px
  space-md: 16px
  space-lg: 24px
  gutter: 16px
  container-max: 1200px
---

## Brand & Style

The design system is built on a foundation of **Modern Minimalism** with a focus on high-density information architecture that remains breathable. The brand personality is professional yet approachable, aiming to evoke a sense of reliability and community. 

The aesthetic prioritizes clarity and content over decorative elements. It utilizes generous whitespace, a systematic grid, and subtle depth to distinguish between the feed background and interactive surfaces. The emotional response should be one of "calm connection"—minimizing visual noise to let user-generated content take center stage.

## Colors

The palette is anchored by a high-energy primary blue used for key actions and brand presence. 

- **Primary (#1877F2):** Reserved for primary buttons, active states, and links.
- **Surface (#FFFFFF):** Used for all content containers (cards, navigation bars, modals) to create a clear "lift" from the background.
- **Background (#F0F2F5):** A cool-tinted neutral that reduces eye strain compared to pure white and provides contrast for surface elements.
- **Typography:** Deep Charcoal (#1C1E21) ensures AAA accessibility for body text, while the secondary gray (#65676B) is used for metadata, icons, and placeholder text.
- **Tertiary/Accent (#E7F3FF):** A soft blue tint used for subtle backgrounds behind primary icons or ghost buttons.

## Typography

The design system utilizes **Inter** for its exceptional legibility and systematic weight distribution. 

- **Readability:** Body-md (15px) is the workhorse for the main social feed, balanced with a 20px line height to ensure long-form posts remain scannable.
- **Hierarchy:** Headlines use tighter letter spacing and heavier weights to stand out against the high frequency of images in the feed.
- **Labels:** Small labels are used for timestamps and secondary metadata, often paired with the secondary gray color.

## Layout & Spacing

This design system employs an **8px linear grid** (with a 4px sub-step) to maintain mathematical harmony. 

- **Grid Strategy:** A centered fixed-width layout is used for desktop (max 1200px) to prevent line lengths from becoming unreadable. The feed itself is typically constrained to a 680px central column.
- **Margins & Gutters:** On mobile, side margins are 16px. On desktop, gutters between the main feed and sidebars are 24px.
- **Padding:** Internal card padding is strictly 16px to ensure content doesn't feel cramped.

## Elevation & Depth

Hierarchy is established through **Tonal Layers** and **Soft Ambient Shadows**.

- **Level 0 (Background):** The #F0F2F5 base layer.
- **Level 1 (Cards/Surfaces):** White surfaces with a very subtle 1px border (#E4E6EB) and a soft shadow (Y: 2px, Blur: 4px, 5% opacity black).
- **Level 2 (Modals/Popovers):** Deeper shadows (Y: 8px, Blur: 16px, 10% opacity black) are used for elements that require immediate user focus.
- **Interactions:** On hover, interactive cards should transition to a slightly deeper shadow or a subtle 2% darker background tint.

## Shapes

The shape language is consistently **Rounded**.

- **Standard Elements:** Buttons, input fields, and small cards use an 8px (0.5rem) radius.
- **Container Elements:** Main feed cards and larger surface areas use a 12px (rounded-lg) radius to feel more approachable.
- **Avatars:** User profile pictures are always circular (pill-shaped) to distinguish human elements from UI elements.

## Components

- **Buttons:** 
  - *Primary:* Filled #1877F2 with white text. 
  - *Secondary:* Light gray background (#E4E6EB) with charcoal text for low-emphasis actions.
  - *State:* 10% black overlay on hover.
- **Cards:** White background, 12px corner radius, 16px internal padding. Cards should not have heavy borders; use the subtle elevation shadow defined in Section 5.
- **Inputs:** 12px vertical padding, 8px corner radius. Background color #F0F2F5 when inactive, transitioning to White with a 2px Primary border on focus.
- **Chips:** Used for categories or tags. 32px height, pill-shaped, using the Tertiary blue background and Primary blue text.
- **Lists:** Flat styling with 1px horizontal dividers (#F0F2F5). Interactive list items use a subtle gray hover state.
- **Feed Item:** A composite component including an Avatar (40x40), Header (Name + Timestamp), Content area, and Action Bar (Like, Comment, Share) with 1px top/bottom borders.