<style>
:root {
    --bg: #FFFAF0;
    --accent: #C06B00;
    --ink: #020202;
    --muted: #868686;
    --soft: #fff3df;
    --line: rgba(192, 107, 0, 0.28);
    --font-main: "Krona One", Arial, sans-serif;
    --font-logo: "Modak", Georgia, serif;
    --font-primary: "Doto", "Courier New", monospace;
}

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
    overflow-x: hidden;
}

body {
    margin: 0;
    min-width: 320px;
    font-family: var(--font-main);
    font-size: 0.7rem;
    color: var(--ink);
    background: var(--bg);
    line-height: 1.6;
}

a {
    text-decoration: none;
    color: inherit;
}

button {
    font-family: inherit;
}

.admin-body {
    padding-top: 0;
    background: #f5f0e8;
}

.admin-layout {
    min-height: 100vh;
}

.admin-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 260px;
    height: 100vh;
    background: var(--ink);
    color: #fff;
    display: flex;
    flex-direction: column;
    padding: 28px 20px;
    z-index: 200;
}

.admin-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 32px;
    font-family: var(--font-logo);
    font-size: 0.85rem;
}

.admin-brand a {
    color: #fff;
}

.admin-badge {
    font-family: var(--font-main);
    font-size: 0.48rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    background: var(--accent);
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
}

.admin-nav {
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
}

.admin-nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 8px;
    color: rgba(255, 255, 255, 0.75);
    font-size: 0.6rem;
    transition: background 0.2s, color 0.2s;
}

.admin-nav-link:hover,
.admin-nav-link.is-active {
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
}

.admin-nav-link svg {
    width: 18px;
    height: 18px;
}

.admin-sidebar-footer {
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.admin-email {
    font-size: 0.6rem;
    color: rgba(255, 255, 255, 0.5);
    word-break: break-all;
}

.admin-logout {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #e63946;
    font-size: 0.6rem;
    padding: 10px 14px;
    border-radius: 8px;
    transition: background 0.2s;
}

.admin-logout:hover {
    background: rgba(230, 57, 70, 0.1);
}

.admin-main {
    margin-left: 260px;
    padding: 32px 36px;
    min-height: 100vh;
}

.admin-header {
    margin-bottom: 28px;
}

.admin-header h1 {
    font-size: 0.8rem;
    font-weight: 400;
    margin: 0;
}

.admin-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 32px;
}

.admin-stat-card {
    background: #fff;
    border: 2px solid var(--line);
    border-radius: 12px;
    padding: 22px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.admin-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: var(--soft);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
}

.admin-stat-icon svg {
    width: 22px;
    height: 22px;
}

.admin-stat-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.admin-stat-value {
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--ink);
}

.admin-stat-label {
    font-size: 0.6rem;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.admin-sections {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.admin-section {
    background: #fff;
    border: 2px solid var(--line);
    border-radius: 12px;
    padding: 22px;
    min-width: 0;
}

.admin-section h2 {
    font-size: 0.7rem;
    font-weight: 400;
    margin: 0 0 16px;
}

.admin-chart-section {
    margin-bottom: 24px;
}

.admin-chart-wrap {
    position: relative;
    height: 320px;
    width: 100%;
}

.admin-chart-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--muted);
    font-size: 0.75rem;
}

.admin-table-wrap {
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.58rem;
}

.admin-table th,
.admin-table td {
    text-align: left;
    padding: 12px 10px;
    border-bottom: 1px solid rgba(192, 107, 0, 0.15);
}

.admin-table td {
    word-break: break-word;
}

.admin-table th {
    color: var(--muted);
    font-weight: 400;
    text-transform: uppercase;
    font-size: 0.58rem;
    letter-spacing: 0.06em;
}

.admin-table tbody tr:last-child td {
    border-bottom: none;
}

.admin-badge-status {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    font-weight: 600;
    text-align: center;
    width: 98px;
    white-space: nowrap;
    vertical-align: middle;
}

.status-pending {
    background: #fff8e1;
    color: #b08d00;
}

.status-processing {
    background: #e3f2fd;
    color: #1565c0;
}

.status-shipped {
    background: #e8f5e9;
    color: #2e7d32;
}

.status-delivered {
    background: #e0f7fa;
    color: #00838f;
}

.status-cancelled {
    background: #ffebee;
    color: #c62828;
}

.status-accepted {
    background: #e8f5e9;
    color: #2e7d32;
}

.status-rejected {
    background: #ffebee;
    color: #c62828;
}

.status-processed {
    background: transparent;
    color: #1a1a1a;
}

.admin-product-thumb {
    width: 44px;
    height: 44px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid var(--line);
}

.admin-select-control {
    position: relative;
    min-height: 34px;
    padding: 0 10px 0 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid var(--line);
    border-radius: 999px;
    background: #fff;
    color: var(--accent);
    font-family: var(--font-primary);
    font-size: 0.58rem;
    font-weight: 800;
    transition: background 180ms ease, border-color 180ms ease, box-shadow 180ms ease;
    cursor: pointer;
}

.admin-select-control:hover {
    border-color: var(--accent);
    background: rgba(255, 250, 240, 0.94);
}

.admin-select-control.is-open {
    border-color: var(--accent);
    background: rgba(255, 250, 240, 0.94);
    box-shadow: 0 8px 22px rgba(192, 107, 0, 0.12);
}

.admin-select-control__label {
    color: rgba(0, 0, 0, 0.58);
    font-size: 0.52rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.admin-select-toggle {
    min-width: 90px;
    border: 0;
    background: transparent;
    color: var(--accent);
    font: inherit;
    font-size: 0.58rem;
    font-weight: 800;
    outline: 0;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 0;
}

.admin-select-toggle svg {
    width: 13px;
    height: 13px;
    stroke-width: 2.6;
    transition: transform 220ms cubic-bezier(0.2, 0.8, 0.2, 1);
}

.admin-select-control.is-open .admin-select-toggle svg {
    transform: rotate(180deg);
}

.admin-select-menu {
    position: absolute;
    top: calc(100% + 6px);
    right: 0;
    z-index: 30;
    min-width: max(100%, 160px);
    max-height: 0;
    padding: 0 6px;
    overflow: hidden;
    border: 1px solid transparent;
    border-radius: 14px;
    background: rgba(255, 250, 240, 0.98);
    box-shadow: 0 14px 36px rgba(0, 0, 0, 0.14);
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transform: translateY(-6px) scale(0.96);
    transform-origin: top right;
    will-change: transform, opacity, max-height;
    transition:
        max-height 260ms cubic-bezier(0.2, 0.8, 0.2, 1),
        padding 260ms cubic-bezier(0.2, 0.8, 0.2, 1),
        opacity 180ms ease,
        transform 260ms cubic-bezier(0.2, 0.8, 0.2, 1),
        border-color 180ms ease,
        visibility 0s linear 260ms;
}

.admin-select-control.is-open .admin-select-menu {
    max-height: 260px;
    padding-block: 5px;
    border-color: var(--line);
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateY(0) scale(1);
    transition:
        max-height 300ms cubic-bezier(0.2, 0.8, 0.2, 1),
        padding 300ms cubic-bezier(0.2, 0.8, 0.2, 1),
        opacity 220ms ease,
        transform 260ms cubic-bezier(0.2, 0.8, 0.2, 1),
        border-color 180ms ease,
        visibility 0s;
}

.admin-select-menu button {
    width: 100%;
    min-height: 30px;
    padding: 0 10px;
    display: flex;
    align-items: center;
    border: 0;
    border-radius: 10px;
    background: transparent;
    color: var(--accent);
    font: inherit;
    font-size: 0.55rem;
    font-weight: 800;
    text-align: left;
    cursor: pointer;
    transition: background 160ms ease, color 160ms ease, transform 160ms ease;
    white-space: nowrap;
}

.admin-select-menu button:hover,
.admin-select-menu button:focus-visible,
.admin-select-menu button.is-selected {
    background: rgba(192, 107, 0, 0.12);
    color: var(--ink);
}

.admin-select-menu button:hover,
.admin-select-menu button:focus-visible {
    transform: translateX(2px);
    outline: 0;
}

.admin-select {
    font: inherit;
    font-size: 0.58rem;
    padding: 6px 10px;
    border: 1.5px solid var(--line);
    border-radius: 6px;
    background: #fff;
    color: var(--ink);
    cursor: pointer;
}

.admin-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 6px;
    border: none;
    background: var(--accent);
    color: #fff;
    font: inherit;
    font-size: 0.58rem;
    cursor: pointer;
    transition: opacity 0.2s;
}

.admin-btn:hover {
    opacity: 0.9;
}

.admin-btn--small {
    padding: 6px 10px;
    font-size: 0.55rem;
}

.admin-btn--danger {
    background: #e63946;
}

.admin-form {
    background: #fff;
    border: 2px solid var(--line);
    border-radius: 12px;
    padding: 22px;
}

.admin-form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px 18px;
}

.admin-form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.admin-form-group--full {
    grid-column: 1 / -1;
}

.admin-form-group label {
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--muted);
}

.admin-form-group input,
.admin-form-group textarea {
    font: inherit;
    font-size: 0.6rem;
    padding: 10px 12px;
    border: 1.5px solid var(--line);
    border-radius: 8px;
    background: #fff;
    color: var(--ink);
    outline: none;
    transition: box-shadow 180ms ease, border-color 180ms ease;
}

.admin-form-group input:focus,
.admin-form-group textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(192, 107, 0, 0.12);
}

.admin-form-actions {
    margin-top: 18px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.admin-alert {
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 0.72rem;
    margin-bottom: 18px;
}

.admin-alert--success {
    background: rgba(34, 139, 34, 0.08);
    color: #228b22;
}

.admin-alert--error {
    background: rgba(192, 40, 40, 0.08);
    color: #c02828;
}

.gallery-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 8px;
}

.gallery-row {
    display: flex;
    gap: 8px;
    align-items: center;
}

.gallery-row input {
    flex: 1;
}

@media (max-width: 1100px) {
    .admin-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .admin-sections {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .admin-form-grid {
        grid-template-columns: 1fr;
    }

    .admin-sidebar {
        position: relative;
        width: 100%;
        height: auto;
        flex-direction: row;
        flex-wrap: wrap;
        padding: 16px;
    }

    .admin-brand {
        margin-bottom: 0;
        flex: 1;
    }

    .admin-nav {
        flex-direction: row;
        flex-wrap: wrap;
        width: 100%;
        margin-top: 12px;
    }

    .admin-sidebar-footer {
        display: none;
    }

    .admin-main {
        margin-left: 0;
        padding: 20px;
    }

    .admin-stats {
        grid-template-columns: 1fr;
    }
}

/* Admin Product Modal */
.admin-product-overlay {
    position: fixed;
    inset: 0;
    z-index: 300;
    display: grid;
    place-items: center;
    padding: 24px;
    background: rgba(0, 0, 0, 0.55);
    opacity: 0;
    visibility: hidden;
    transition: opacity 280ms ease, visibility 0s linear 280ms;
}

.admin-product-overlay.is-open {
    opacity: 1;
    visibility: visible;
    transition: opacity 280ms ease, visibility 0s;
}

.admin-product-modal {
    width: min(720px, 100%);
    max-height: calc(100vh - 48px);
    overflow-y: auto;
    padding: clamp(28px, 3vw, 44px) clamp(24px, 2.8vw, 40px);
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 32px 80px rgba(0, 0, 0, 0.28);
    transform: translateY(20px) scale(0.97);
    transition: transform 360ms cubic-bezier(0.2, 0.8, 0.2, 1);
    position: relative;
    scrollbar-width: none;
}

.admin-product-overlay.is-open .admin-product-modal {
    transform: translateY(0) scale(1);
}

.admin-product-modal::-webkit-scrollbar {
    display: none;
}

.admin-product-modal__close {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 0;
    border-radius: 10px;
    background: rgba(0, 0, 0, 0.06);
    color: var(--ink);
    cursor: pointer;
    transition: background 180ms ease, transform 180ms ease;
    z-index: 2;
}

.admin-product-modal__close:hover {
    background: rgba(0, 0, 0, 0.12);
    transform: rotate(90deg);
}

.admin-product-modal__close svg {
    width: 18px;
    height: 18px;
}

.admin-product-modal__media {
    margin-top: 24px;
    margin-bottom: 20px;
}

.admin-product-modal__hero {
    width: 100%;
    aspect-ratio: 16 / 9;
    object-fit: cover;
    border-radius: 12px;
    background: #ececec;
    display: block;
}

.admin-product-modal__gallery {
    display: flex;
    gap: 8px;
    margin-top: 10px;
    overflow-x: auto;
    scrollbar-width: none;
}

.admin-product-modal__gallery::-webkit-scrollbar {
    display: none;
}

.admin-product-modal__thumb {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border-radius: 8px;
    background: #ececec;
    flex-shrink: 0;
}

.admin-product-modal__info h2 {
    margin: 0 0 10px;
    color: var(--accent);
    font-size: clamp(1rem, 1.4vw, 1.3rem);
    line-height: 1.2;
    font-weight: 400;
}

.admin-product-modal__badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    background: rgba(192, 107, 0, 0.12);
    color: var(--accent);
    font-size: 0.55rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 16px;
}

.admin-product-modal__meta {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px 18px;
    margin-bottom: 18px;
}

.admin-product-modal__meta > div {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.admin-product-modal__meta > div span {
    font-size: 0.52rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--muted);
}

.admin-product-modal__meta > div strong {
    font-size: 0.68rem;
    font-weight: 600;
    color: var(--ink);
}

.admin-product-modal__tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 16px;
}

.admin-product-modal__tags span {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    background: rgba(0, 0, 0, 0.05);
    color: var(--ink);
    font-size: 0.55rem;
}

.admin-product-modal__desc {
    margin: 0 0 18px;
    font-size: 0.62rem;
    line-height: 1.6;
    color: var(--ink);
}

.admin-product-modal__foot {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 18px;
    padding-top: 14px;
    border-top: 1px solid rgba(192, 107, 0, 0.12);
}

.admin-product-modal__foot span {
    font-size: 0.52rem;
    color: var(--muted);
}

@media (max-width: 600px) {
    .admin-product-overlay {
        padding: 12px;
    }

    .admin-product-modal {
        max-height: calc(100vh - 24px);
        padding: 22px 18px;
        border-radius: 14px;
    }

    .admin-product-modal__meta {
        grid-template-columns: 1fr 1fr;
    }
}

/* Admin panel toggles (same UX as shop accordions) */
.admin-toggle {
    justify-content: center;
    position: relative;
    padding: 10px 18px;
    padding-right: 38px;
    border-radius: 999px;
    border: 1px solid var(--line);
    background: #fff;
    color: var(--accent);
    font-family: var(--font-primary);
    font-size: 0.58rem;
    font-weight: 800;
    transition: background 180ms ease, border-color 180ms ease, box-shadow 180ms ease;
}

.admin-toggle:hover {
    border-color: var(--accent);
    background: rgba(255, 250, 240, 0.94);
    box-shadow: 0 8px 22px rgba(192, 107, 0, 0.12);
}

.admin-toggle[aria-expanded="true"] {
    border-color: var(--accent);
    background: rgba(255, 250, 240, 0.94);
    box-shadow: 0 8px 22px rgba(192, 107, 0, 0.12);
}

.admin-toggle__icon {
    position: absolute;
    right: 14px;
    width: 14px;
    height: 14px;
    stroke-width: 2.6;
    transition: transform 220ms cubic-bezier(0.2, 0.8, 0.2, 1);
}

.admin-toggle[aria-expanded="true"] .admin-toggle__icon {
    transform: rotate(180deg);
}

.admin-panel-content {
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    margin-top: 0;
    transition: max-height 360ms cubic-bezier(0.2, 0.8, 0.2, 1),
                opacity 220ms ease,
                margin-top 360ms cubic-bezier(0.2, 0.8, 0.2, 1);
}

.admin-panel-content.is-open {
    opacity: 1;
    margin-top: 18px;
    max-height: none;
}

.admin-panel-content[hidden] {
    display: none;
}
</style>
