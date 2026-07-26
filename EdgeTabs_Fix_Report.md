# Edge Tabs Sanitization Report

## Statistik

| Metrik | Nilai |
|--------|-------|
| Total tabs input | 3 |
| Total tabs output | 3 |
| Current tab setelah sanitasi | tabId=115065325 (Bing images search) |
| Jumlah redaksi query param | 0 |
| Jumlah invalid_url | 1 |
| Jumlah normalized_tabId | 1 |
| Jumlah placeholder/empty titles | 1 |

## Contoh Sanitized Objects

### Objek 0 — placeholder (wrapper-only, tidak valid)
```json
{
  "pageTitle": "",
  "pageUrl": null,
  "tabId": null,
  "isCurrent": false,
  "notes": ["invalid_url", "normalized_tabId"]
}
```

### Objek 1 — Bing images search (current tab)
```json
{
  "pageTitle": "foto sampul anime landscape - Search",
  "pageUrl": "https://www.bing.com/images/search",
  "tabId": 115065325,
  "isCurrent": true,
  "notes": []
}
```

### Objek 2 — PR #1 SocialFeed
```json
{
  "pageTitle": "https://github.com/Mank-Krisna/SocialFeed/pull/1",
  "pageUrl": "https://github.com/Mank-Krisna/SocialFeed/pull/1",
  "tabId": 115065451,
  "isCurrent": false,
  "notes": []
}
```

## Catatan

- Tidak ada query param sensitif pada input kali ini.
- Tab 0 (wrapper-only, tabId=-1) dinormalisasi jadi placeholder; isCurrent dipindahkan ke Tab 1.
- Jika ingin mempertahankan isCurrent asli (Tab 0), simpan flag sementara lalu tetapkan ke objek pertama setelah validasi.
