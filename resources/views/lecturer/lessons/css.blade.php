<style>
/*
 * Lesson Editor & Content Styles
 * Shared between: create.blade.php, edit.blade.php, show.blade.php
 * ================================================================
 */

/* =============================================================
   QUILL EDITOR CONTAINER
   ============================================================= */
#editor-container {
    display: flex;
    flex-direction: column;
    height: 100%;
}

/* Toolbar Styling */
.ql-toolbar.ql-snow {
    border: none !important;
    border-bottom: 1px solid #e5e7eb !important;
    background: #f9fafb;
    padding: 12px 16px !important;
    flex-shrink: 0;
    z-index: 10;
    position: sticky;
    top: 0;
}

.ql-container.ql-snow {
    border: none !important;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 16px;
    line-height: 1.6;
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    background: #fff;
}

/* =============================================================
   EDITOR & CONTENT TYPOGRAPHY
   (Shared between .ql-editor and .lesson-content)
   ============================================================= */
.ql-editor,
.lesson-content {
    width: 100%;
    padding: 2rem 1.5rem !important;
    min-height: 100%;
    
    /* Typography - Consistent across Edit & View */
    font-size: 1.0625rem !important;
    line-height: 1.75 !important;
    color: #1f2937 !important;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    -webkit-font-smoothing: antialiased;
    position: relative;
}

.lesson-content {
    max-width: 42rem;
    margin: 0 auto;
    padding: 0 !important;
}

/* HEADINGS */
.ql-editor h1, .ql-editor h2, .ql-editor h3,
.lesson-content h1, .lesson-content h2, .lesson-content h3 {
    font-weight: 700 !important;
    color: #111827 !important;
    line-height: 1.3 !important;
    margin-top: 2.5rem !important;
    margin-bottom: 1rem !important;
    letter-spacing: -0.025em !important;
}

.ql-editor h1, .lesson-content h1 { font-size: 2.25rem !important; margin-top: 0 !important; }
.ql-editor h2, .lesson-content h2 { font-size: 1.875rem !important; }
.ql-editor h3, .lesson-content h3 { font-size: 1.5rem !important; }

/* PARAGRAPHS */
.ql-editor p, .lesson-content p { 
    margin-bottom: 1.5rem !important; 
}

.ql-editor strong, .lesson-content strong { 
    font-weight: 700 !important; 
    color: #0f172a !important; 
}

/* LINKS */
.ql-editor a, .lesson-content a {
    color: #2563eb !important;
    text-decoration: underline !important;
    text-decoration-thickness: 1px !important;
    text-underline-offset: 2px !important;
    font-weight: 500 !important;
}

.lesson-content a:hover {
    color: #1d4ed8;
}

/* LISTS */
/* LISTS */
/* Quill Editor - Force standard HTML lists to override Tailwind resets and Quill's complexity */
.ql-editor ul {
    list-style-type: disc !important;
    padding-left: 2rem !important;
    margin: 1rem 0 !important;
}

.ql-editor ol {
    list-style-type: decimal !important;
    padding-left: 2rem !important;
    margin: 1rem 0 !important;
}

.ql-editor li {
    display: list-item !important;
    list-style-position: outside !important;
    margin-left: 0 !important; /* Reset margin since we use padding on UL/OL */
}

/* Lesson Content (View) - Custom rich styling */
.lesson-content ul, .lesson-content ol {
    margin: 1.5rem 0 !important;
    padding-left: 2rem !important;
}

.lesson-content ul { 
    list-style-type: disc !important; 
}

.lesson-content ol { 
    list-style-type: decimal !important; 
}

.lesson-content li { 
    margin-bottom: 0.75rem !important;
    padding-left: 0.5rem !important;
    display: list-item !important;
}

/* Editor LI - Minimal override, let Quill handle display/bullets */
.ql-editor li {
    margin-bottom: 0.5rem !important;
}

/* BLOCKQUOTE */
.ql-editor blockquote, .lesson-content blockquote {
    border-left: 4px solid #3b82f6 !important;
    padding: 1.25rem 1.5rem !important;
    margin: 2rem 0 !important;
    color: #475569 !important;
    font-style: italic !important;
    font-size: 1.125rem !important;
    background: #f8fafc !important;
    border-radius: 0 4px 4px 0 !important;
}

/* CODE */
.ql-editor code, .lesson-content code {
    background: #f1f5f9 !important;
    color: #dc2626 !important;
    padding: 0.2em 0.4em !important;
    border-radius: 3px !important;
    font-family: 'SF Mono', Monaco, Consolas, monospace !important;
    font-size: 0.9em !important;
    font-weight: 500 !important;
}

.ql-editor pre.ql-syntax, .lesson-content pre {
    background: #1e293b !important;
    color: #f1f5f9 !important;
    padding: 1.5rem !important;
    border-radius: 8px !important;
    overflow-x: auto !important;
    margin: 2rem 0 !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
}

.lesson-content pre code {
    background: none !important;
    padding: 0 !important;
    color: #f1f5f9 !important;
    font-size: 0.9375rem !important;
}

/* IMAGES */
.lesson-content img {
    max-width: 100%;
    height: auto;
    margin: 2.5rem auto;
    display: block;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* TABLES */
.lesson-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    border: 1px solid #e5e7eb;
}

.lesson-content th,
.lesson-content td {
    border: 1px solid #e5e7eb;
    padding: 0.75rem 1rem;
    text-align: left;
}

.lesson-content th {
    background: #f9fafb;
    font-weight: 600;
    color: #111827;
}

.lesson-content tr:nth-child(even) {
    background: #f9fafb;
}

.lesson-content tr:hover {
    background: #f3f4f6;
}

/* =============================================================
   QUILL EDITOR PLACEHOLDER
   ============================================================= */
.ql-editor.ql-blank::before {
    left: 1.5rem;
    right: 1.5rem;
    font-style: normal;
    color: #9ca3af;
    font-size: 1.0625rem;
}

/* =============================================================
   QUILL TOOLBAR BUTTONS
   ============================================================= */
.ql-toolbar button {
    width: 32px !important;
    height: 32px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.2s ease;
}

.ql-toolbar button:hover {
    background: #e5e7eb;
    border-radius: 4px;
}

.ql-toolbar button.ql-active {
    background: #dbeafe !important;
    border-radius: 4px;
}

.ql-toolbar .ql-picker {
    transition: all 0.2s ease;
}

.ql-toolbar .ql-picker:hover .ql-picker-label {
    background: #e5e7eb;
    border-radius: 4px;
}

.ql-toolbar button svg {
    width: 18px !important;
    height: 18px !important;
    stroke-width: 1.5 !important;
}

/* =============================================================
   CUSTOM TOOLBAR ICONS (Modern Thin-Stroke SVGs)
   ============================================================= */

/* Hide default SVGs */
.ql-toolbar .ql-bold svg, .ql-toolbar .ql-italic svg, .ql-toolbar .ql-underline svg, 
.ql-toolbar .ql-strike svg, .ql-toolbar .ql-blockquote svg, .ql-toolbar .ql-code-block svg,
.ql-toolbar .ql-list svg, .ql-toolbar .ql-link svg, .ql-toolbar .ql-image svg, 
.ql-toolbar .ql-clean svg, .ql-toolbar .ql-indent svg {
    display: none;
}

/* Custom Icon Definitions */
.ql-toolbar .ql-bold::after { content: ''; display: block; width: 18px; height: 18px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z'/%3E%3Cpath d='M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z'/%3E%3C/svg%3E") no-repeat center; }
.ql-toolbar .ql-italic::after { content: ''; display: block; width: 18px; height: 18px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='19' y1='4' x2='10' y2='4'/%3E%3Cline x1='14' y1='20' x2='5' y2='20'/%3E%3Cline x1='15' y1='4' x2='9' y2='20'/%3E%3C/svg%3E") no-repeat center; }
.ql-toolbar .ql-underline::after { content: ''; display: block; width: 18px; height: 18px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3'/%3E%3Cline x1='4' y1='21' x2='20' y2='21'/%3E%3C/svg%3E") no-repeat center; }
.ql-toolbar .ql-strike::after { content: ''; display: block; width: 18px; height: 18px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M16 4H9a3 3 0 0 0-2.83 4'/%3E%3Cpath d='M14 12a4 4 0 0 1 0 8H6'/%3E%3Cline x1='4' y1='12' x2='20' y2='12'/%3E%3C/svg%3E") no-repeat center; }
.ql-toolbar .ql-blockquote::after { content: ''; display: block; width: 18px; height: 18px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z'/%3E%3Cpath d='M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z'/%3E%3C/svg%3E") no-repeat center; }
.ql-toolbar .ql-code-block::after { content: ''; display: block; width: 18px; height: 18px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='16 18 22 12 16 6'/%3E%3Cpolyline points='8 6 2 12 8 18'/%3E%3C/svg%3E") no-repeat center; }
.ql-toolbar .ql-list[value='ordered']::after { content: ''; display: block; width: 18px; height: 18px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='10' y1='6' x2='21' y2='6'/%3E%3Cline x1='10' y1='12' x2='21' y2='12'/%3E%3Cline x1='10' y1='18' x2='21' y2='18'/%3E%3Cpath d='M4 6h1v4'/%3E%3Cpath d='M4 10h2'/%3E%3Cpath d='M6 18H4c0-1 2-2 2-3s-1-1.5-2-1'/%3E%3C/svg%3E") no-repeat center; }
.ql-toolbar .ql-list[value='bullet']::after { content: ''; display: block; width: 18px; height: 18px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='8' y1='6' x2='21' y2='6'/%3E%3Cline x1='8' y1='12' x2='21' y2='12'/%3E%3Cline x1='8' y1='18' x2='21' y2='18'/%3E%3Cline x1='3' y1='6' x2='3.01' y2='6'/%3E%3Cline x1='3' y1='12' x2='3.01' y2='12'/%3E%3Cline x1='3' y1='18' x2='3.01' y2='18'/%3E%3C/svg%3E") no-repeat center; }
.ql-toolbar .ql-link::after { content: ''; display: block; width: 18px; height: 18px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71'/%3E%3Cpath d='M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71'/%3E%3C/svg%3E") no-repeat center; }
.ql-toolbar .ql-image::after { content: ''; display: block; width: 18px; height: 18px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='3' width='18' height='18' rx='2' ry='2'/%3E%3Ccircle cx='8.5' cy='8.5' r='1.5'/%3E%3Cpolyline points='21 15 16 10 5 21'/%3E%3C/svg%3E") no-repeat center; }
.ql-toolbar .ql-clean::after { content: ''; display: block; width: 18px; height: 18px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='3 6 5 6 21 6'/%3E%3Cpath d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'/%3E%3C/svg%3E") no-repeat center; }
.ql-toolbar .ql-indent[value='-1']::after { content: ''; display: block; width: 18px; height: 18px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='21' y1='6' x2='3' y2='6'/%3E%3Cline x1='15' y1='12' x2='3' y2='12'/%3E%3Cline x1='15' y1='18' x2='3' y2='18'/%3E%3C/svg%3E") no-repeat center; }
.ql-toolbar .ql-indent[value='+1']::after { content: ''; display: block; width: 18px; height: 18px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='21' y1='6' x2='3' y2='6'/%3E%3Cline x1='21' y1='12' x2='9' y2='12'/%3E%3Cline x1='21' y1='18' x2='9' y2='18'/%3E%3C/svg%3E") no-repeat center; }
</style>
