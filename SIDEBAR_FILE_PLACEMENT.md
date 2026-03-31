# Sidebar file placement

Because this repository currently has no frontend app structure (no `templates/`, `src/`, or `public/` folders), the sidebar files were initially added at the repo root.

When integrating into a real admin panel, place them as follows:

- `admin-sidebar.html` → your admin layout/view template (for example: `templates/admin/layout.html` or `src/layouts/AdminLayout.jsx`).
- `admin-sidebar.css` → your global/static styles location (for example: `public/css/admin-sidebar.css` or `src/styles/admin-sidebar.css`).
- `admin-sidebar.js` → your frontend scripts location (for example: `public/js/admin-sidebar.js` or `src/js/admin-sidebar.js`).

## Minimal include wiring

If using server-rendered HTML:

```html
<link rel="stylesheet" href="/css/admin-sidebar.css" />
<script defer src="/js/admin-sidebar.js"></script>
```

If using React/Vite/Webpack:

- Import `admin-sidebar.css` in your admin entry/component.
- Move the sidebar markup into your admin layout component.
- Initialize or adapt the JS behavior in your component lifecycle/hooks.
