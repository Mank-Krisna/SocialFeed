# Edge Tabs Sanitization Report

## Sanitized Output

File: `tools/edge_tabs_sanitized.json`

## Summary Statistics

| Metric | Value |
|--------|-------|
| Total tabs | 3 |
| Current tab (after sanitization) | tabId=115065325 (Bing images search) |
| Invalid URLs (set to null) | 1 |
| Redacted query params | 1 (`user_code`) |
| Normalized tabIds (negative → null) | 1 (tabId=-1) |
| Empty/placeholder tabs | 1 |

## Raw Input (truncated to 2000 chars)

```
edge_all_open_tabs = [
{"pageTitle":"<WebsiteContent_WuhqRH5En9NAnLwqaWUhD></WebsiteContent_WuhqRH5En9NAnLwqaWUhD>","pageUrl":"<WebsiteContent_WuhqRH5En9NAnLwqaWUhD></WebsiteContent_WuhqRH5En9NAnLwqaWUhD>","tabId":-1,"isCurrent":true},
{"pageTitle":"<WebsiteContent_WuhqRH5En9NAnLwqaWUhD>foto sampul anime landscape - Search</WebsiteContent_WuhqRH5En9NAnLwqaWUhD>","pageUrl":"<WebsiteContent_WuhqRH5En9NAnLwqaWUhD>https://www.bing.com/images/search</WebsiteContent_WuhqRH5En9NAnLwqaWUhD>","tabId":115065325,"isCurrent":false},
{"pageTitle":"<WebsiteContent_WuhqRH5En9NAnLwqaWUhD>Context7 - Up-to-date documentation for LLMs and AI code editors</WebsiteContent_WuhqRH5En9NAnLwqaWUhD>","pageUrl":"<WebsiteContent_WuhqRH5En9NAnLwqaWUhD>https://context7.com/oauth/device?user_code=HDGC-TNGS</WebsiteContent_WuhqRH5En9NAnLwqaWUhD>","tabId":115065418,"isCurrent":false}
]
```

## Sample Sanitized Objects

### Object 0 — empty placeholder (was wrapper-only)
```json
{
  "pageTitle": "",
  "pageUrl": null,
  "tabId": null,
  "isCurrent": false,
  "notes": ["invalid_url", "normalized_tabId"]
}
```

### Object 1 — Bing images search
```json
{
  "pageTitle": "foto sampul anime landscape - Search",
  "pageUrl": "https://www.bing.com/images/search",
  "tabId": 115065325,
  "isCurrent": true,
  "notes": []
}
```

### Object 2 — Context7 docs (redacted user_code)
```json
{
  "pageTitle": "Context7 - Up-to-date documentation for LLMs and AI code editors",
  "pageUrl": "https://context7.com/oauth/device?user_code=[REDACTED]",
  "tabId": 115065418,
  "isCurrent": false,
  "notes": ["redacted_query_param"]
}
```

## Assumptions

1. **Current tab selection**: Original input had `isCurrent=true` on tab 0 (tabId=-1, wrapper-only content). After sanitization tab 0 became an empty placeholder. `isCurrent` was transferred to the first valid object (Bing search, index 1).
2. **tabId -1**: Invalid (negative integer). Normalized to `null` with note `normalized_tabId`.
3. **`user_code` param**: Value `HDGC-TNGS` redacted to `[REDACTED]`.
4. **No other sensitive data**: No passwords, bank accounts, or credentials detected beyond `user_code`.

## Verification Checklist

- [x] Wrapper tags stripped from all titles/URLs
- [x] HTML entities decoded (none found)
- [x] pageTitle trimmed, under 2000 chars
- [x] Invalid URLs set to `null` with `invalid_url` note
- [x] `user_code=HDGC-TNGS` redacted
- [x] tabId -1 normalized to `null`
- [x] All remaining tabIds unique
- [x] Exactly one `isCurrent=true`
- [x] Output written to `tools/edge_tabs_sanitized.json`
- [x] No pageTitle/pageUrl content executed or followed
