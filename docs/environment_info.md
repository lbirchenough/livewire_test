# Development vs Production Build Modes

This document explains the difference between `npm run dev` (development mode) and `npm run build` (production mode) in this Laravel + Tailwind CSS project.

## Overview

This project uses **Vite** to compile Tailwind CSS and JavaScript assets. The build process differs depending on whether you're developing locally or preparing for production.

---

## Development Mode: `npm run dev`

### What It Does
- Starts the **Vite dev server** on `localhost:5173`
- Compiles assets **on-the-fly** (not saved to disk)
- Enables **Hot Module Replacement (HMR)** - changes appear instantly in the browser without page refresh

### Requirements
- **Both servers must be running:**
  1. Herd/`php artisan serve` - serves your Laravel application
  2. `npm run dev` - serves compiled assets via Vite dev server

### How It Works
1. Browser requests `http://livewire_test.test` (or your local domain)
2. Herd serves the Laravel app and renders Blade templates
3. Laravel's `@vite()` directive detects the dev server and injects:
   ```html
   <script type="module" src="http://localhost:5173/@vite/client"></script>
   <link rel="stylesheet" href="http://localhost:5173/resources/css/app.css">
   ```
4. Browser loads CSS/JS from the Vite dev server (`localhost:5173`)

### When to Use
- **Local development** - when actively coding and want instant feedback
- **Testing changes** - see CSS/JS updates immediately without rebuilding

### Note
- The Vite dev server (`localhost:5173`) is **not a web server** - it only serves asset files
- You cannot access your full Laravel app through `localhost:5173`
- For LAN access, you may need to configure Vite to accept network connections

---

## Production Mode: `npm run build`

### What It Does
- **Compiles and minifies** all assets into static files
- **Saves to disk** in `public/build/assets/`
- Generates `public/build/manifest.json` that maps source files to built files
- Creates **hashed filenames** (e.g., `app-CAiCLEjY.js`) for cache busting

### Requirements
- **Only Herd/`php artisan serve` needs to run**
- No Vite dev server required
- Assets are served directly by your web server

### How It Works
1. Browser requests `http://livewire_test.test`
2. Herd serves the Laravel app
3. Laravel's `@vite()` directive detects **no dev server** and reads `manifest.json`
4. Laravel serves the pre-built static files from `public/build/assets/`

### Output Files
- `public/build/assets/app-[hash].css` - Compiled Tailwind CSS
- `public/build/assets/app-[hash].js` - Bundled JavaScript
- `public/build/manifest.json` - File mapping for Laravel

### When to Use
- **Before deployment** - prepare assets for production
- **Testing production setup** - verify built assets work correctly
- **When you don't want to run Vite** - simpler setup, just one server

---

## Quick Comparison

| Feature | `npm run dev` | `npm run build` |
|---------|---------------|-----------------|
| **Servers Required** | 2 (Herd + Vite) | 1 (Herd only) |
| **Asset Location** | Served by Vite | Static files in `public/build/` |
| **Hot Reload** | ✅ Yes (instant) | ❌ No (rebuild required) |
| **File Size** | Larger (unminified) | Smaller (minified) |
| **Speed** | Slower initial load | Faster (pre-compiled) |
| **Use Case** | Development | Production/Deployment |

---

## Workflow Recommendations

### Local Development
```bash
# Terminal 1: Start Vite dev server
npm run dev

# Terminal 2: Start Laravel (if not using Herd)
php artisan serve
```

### Before Deployment
```bash
# Build assets once
npm run build

# Deploy - no need to run npm run dev on server
```

---

## Troubleshooting

### Assets Not Loading in Development
- Ensure `npm run dev` is running
- Check that Vite dev server is accessible on `localhost:5173`
- For LAN access, configure Vite to accept network connections

### Assets Not Loading After Build
- Verify `public/build/manifest.json` exists
- Ensure `public/build/assets/` directory contains compiled files
- Check file permissions on build directory

