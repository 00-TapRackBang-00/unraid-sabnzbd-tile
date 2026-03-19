# SABnzbd Tile — Unraid Dashboard Plugin

An Unraid dashboard tile showing your SABnzbd active download queue and recent history. Auto-refreshes every 10 seconds.

## Features

- **Active queue**: Up to 3 downloading items with animated progress bars, size, and ETA
- **Recent history**: Last 4 completed/failed downloads with status indicators
- **Header stats**: Current download speed and total ETA
- **Pause detection**: Progress bars turn grey when SABnzbd is paused
- **Settings page**: Configure your SABnzbd URL and API key with a built-in connection test

## Installation

Paste the following URL into **Unraid → Plugins → Install Plugin**:

```
https://raw.githubusercontent.com/00-TapRackBang-00/unraid-sabnzbd-tile/main/sabnzbd-tile.plg
```

**Requires Unraid 6.11.9+**

## Configuration

After installing, go to **Settings → SABnzbd Tile** and enter:

- **SABnzbd URL** — e.g. `http://192.168.1.100:8080`
- **API Key** — found in SABnzbd under *Config → General → Security → API Key*

Click **Test Connection** to verify, then **Apply** to save.

## Screenshot

The tile appears automatically on your Unraid dashboard showing:

```
[↓] SABnzbd                    120.9 M
    3 Downloading         ETA 0:39:58   [⚙]

  DOWNLOADING
  The.Unit.S01E13.1080p.WEB-DL  tv
  1.5 MB / 5.0 GB · 0:00:00
  ████████████████████ 99%

  RECENT
  ✓ The.Unit.S01E11.1080p   tv   4.6 GB
  ✓ The.Unit.S01E12.1080p   tv   4.6 GB
  ✓ The.Unit.S01E09.1080p   tv   4.5 GB
  ✓ The.Unit.S01E07.1080p   tv   4.5 GB
```

## License

MIT
