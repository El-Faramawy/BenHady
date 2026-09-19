<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BenHady API Documentation</title>
    <meta name="description" content="Complete interactive API reference and documentation for BenHady Car Rental platform.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Highlight.js for Code Highlighting -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/json.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/http.min.js"></script>

    <!-- Marked.js for Markdown Parsing -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <style>
        :root {
            --bg-body: #090d16;
            --bg-sidebar: #0f172a;
            --bg-card: #131d33;
            --bg-card-hover: #182440;
            --bg-code: #0b1120;
            --border-color: #1e293b;
            --border-glow: rgba(59, 130, 246, 0.25);
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --text-subtle: #64748b;
            --primary: #3b82f6;
            --primary-glow: rgba(59, 130, 246, 0.4);
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --accent-indigo: #6366f1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.65;
        }

        /* Sidebar Styling */
        #sidebar {
            width: 320px;
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-header {
            padding: 1.5rem 1.25rem 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(180deg, rgba(30, 41, 59, 0.4) 0%, transparent 100%);
        }

        .logo-area {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .brand-title {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            background: linear-gradient(135deg, #ffffff 0%, #93c5fd 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .badge-version {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.5rem;
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .search-box {
            position: relative;
        }

        .search-input {
            width: 100%;
            background: var(--bg-code);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            padding: 0.6rem 0.75rem 0.6rem 2.2rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-subtle);
            pointer-events: none;
        }

        .nav-tree {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0.75rem;
        }

        .nav-tree::-webkit-scrollbar {
            width: 5px;
        }

        .nav-tree::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        .nav-item {
            display: block;
            padding: 0.45rem 0.75rem;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.83rem;
            font-weight: 500;
            border-radius: 0.375rem;
            margin-bottom: 0.15rem;
            transition: all 0.15s ease;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-item:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(3px);
        }

        .nav-item.active {
            color: #ffffff;
            background: rgba(59, 130, 246, 0.18);
            border-left: 3px solid var(--primary);
            font-weight: 600;
        }

        .nav-item.level-2 {
            font-weight: 700;
            color: #e2e8f0;
            margin-top: 0.85rem;
            margin-bottom: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-size: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding-bottom: 0.35rem;
        }

        .nav-item.level-3 {
            padding-left: 1.25rem;
        }

        .sidebar-footer {
            padding: 0.85rem 1.25rem;
            border-top: 1px solid var(--border-color);
            background: rgba(15, 23, 42, 0.6);
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            flex: 1;
            padding: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 0.375rem;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-muted);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        /* Main Container */
        #main {
            margin-left: 320px;
            flex: 1;
            padding: 2.5rem 3.5rem;
            max-width: 1200px;
        }

        /* Mobile Header */
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: var(--bg-sidebar);
            border-bottom: 1px solid var(--border-color);
            padding: 0 1.25rem;
            align-items: center;
            justify-content: space-between;
            z-index: 40;
        }

        .btn-hamburger {
            background: none;
            border: none;
            color: var(--text-main);
            cursor: pointer;
            padding: 0.5rem;
        }

        /* Top Bar Banner */
        .top-banner {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.3) 0%, rgba(15, 23, 42, 0.5) 100%);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 0.75rem;
            padding: 1.25rem 1.75rem;
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            backdrop-filter: blur(8px);
        }

        .banner-info h2 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.25rem;
        }

        .banner-info p {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .base-url-pill {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.5rem 0.85rem;
            border-radius: 0.5rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            color: #38bdf8;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Markdown Rendered Content Styling */
        #content h1 {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
            color: #ffffff;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.75rem;
        }

        #content h2 {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-top: 3rem;
            margin-bottom: 1rem;
            color: #f8fafc;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
            scroll-margin-top: 2rem;
        }

        #content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 0.85rem;
            color: #93c5fd;
            scroll-margin-top: 2rem;
        }

        #content h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.6rem;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        #content p {
            margin-bottom: 1.15rem;
            color: #cbd5e1;
            font-size: 0.95rem;
        }

        #content hr {
            border: none;
            border-top: 1px solid var(--border-color);
            margin: 2.5rem 0;
        }

        #content blockquote {
            background: rgba(30, 41, 59, 0.4);
            border-left: 4px solid var(--primary);
            padding: 1rem 1.25rem;
            border-radius: 0 0.5rem 0.5rem 0;
            margin-bottom: 1.5rem;
            color: #94a3b8;
        }

        #content ul, #content ol {
            margin-bottom: 1.25rem;
            padding-left: 1.5rem;
            color: #cbd5e1;
            font-size: 0.95rem;
        }

        #content li {
            margin-bottom: 0.4rem;
        }

        /* Tables */
        #content table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 1.25rem 0 2rem 0;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            font-size: 0.88rem;
        }

        #content th {
            background: rgba(15, 23, 42, 0.85);
            color: #f8fafc;
            text-align: left;
            padding: 0.75rem 1rem;
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            letter-spacing: 0.02em;
        }

        #content td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            color: #cbd5e1;
        }

        #content tr:last-child td {
            border-bottom: none;
        }

        #content tr:nth-child(even) td {
            background: rgba(255, 255, 255, 0.015);
        }

        #content tr:hover td {
            background: rgba(59, 130, 246, 0.06);
        }

        /* Code Blocks */
        #content pre {
            background: var(--bg-code);
            border: 1px solid var(--border-color);
            border-radius: 0.6rem;
            padding: 1.2rem;
            overflow-x: auto;
            margin: 1rem 0 1.75rem 0;
            position: relative;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.5);
        }

        #content code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85em;
        }

        #content p code, #content li code, #content td code {
            background: rgba(59, 130, 246, 0.12);
            color: #93c5fd;
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 0.15rem 0.4rem;
            border-radius: 0.3rem;
            font-weight: 500;
        }

        /* Copy Button */
        .copy-btn {
            position: absolute;
            top: 0.6rem;
            right: 0.6rem;
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            border-radius: 0.35rem;
            padding: 0.25rem 0.6rem;
            font-size: 0.72rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            backdrop-filter: blur(4px);
        }

        .copy-btn:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        /* HTTP Method Badges */
        .badge-method {
            display: inline-block;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.15rem 0.5rem;
            border-radius: 0.3rem;
            margin-right: 0.4rem;
            text-transform: uppercase;
        }

        .badge-get {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.4);
        }

        .badge-post {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }

        .badge-put {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.4);
        }

        .badge-delete {
            background: rgba(244, 63, 94, 0.15);
            color: #fb7185;
            border: 1px solid rgba(244, 63, 94, 0.4);
        }

        /* Floating back to top */
        #btn-back-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 30;
        }

        #btn-back-top.visible {
            opacity: 1;
            transform: translateY(0);
        }

        #btn-back-top:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 15px var(--primary-glow);
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.open {
                transform: translateX(0);
                box-shadow: 10px 0 30px rgba(0,0,0,0.7);
            }

            #main {
                margin-left: 0;
                padding: 5rem 1.5rem 2rem 1.5rem;
                width: 100%;
            }

            .mobile-header {
                display: flex;
            }
        }
    </style>
</head>
<body>

    <!-- Mobile Header -->
    <div class="mobile-header">
        <button class="btn-hamburger" id="mobile-toggle" aria-label="Toggle Navigation">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <span class="brand-title" style="font-size: 1.1rem;">BenHady API</span>
        <a href="{{ url('api_docs.md') }}" class="btn-action" style="padding: 0.35rem 0.6rem;">.MD</a>
    </div>

    <!-- Sidebar -->
    <aside id="sidebar">
        <div class="sidebar-header">
            <div class="logo-area">
                <div class="brand-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5">
                        <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C2.1 10.7 2 11 2 11.4V16c0 .6.4 1 1 1h2"/>
                        <circle cx="7" cy="17" r="2"/>
                        <circle cx="17" cy="17" r="2"/>
                    </svg>
                    <span>BenHady</span>
                </div>
                <span class="badge-version">v1.0 API</span>
            </div>
            <div class="search-box">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" id="search-input" class="search-input" placeholder="Search endpoints, methods...">
            </div>
        </div>

        <nav class="nav-tree" id="nav-tree">
            <!-- Dynamic Navigation injected via JS -->
        </nav>

        <div class="sidebar-footer">
            <a href="{{ url('api_docs.md') }}" target="_blank" class="btn-action" title="View or download raw Markdown file">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                Raw .MD
            </a>
            <a href="{{ url('api_docs?format=raw') }}" download="benhady_api_docs.md" class="btn-action" title="Download Markdown document">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Download
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main id="main">
        <!-- Top Banner with Base URL Quick Copy -->
        <div class="top-banner">
            <div class="banner-info">
                <h2>BenHady API Documentation</h2>
                <p>Interactive RESTful reference for web, mobile apps, and dashboard integrations.</p>
            </div>
            <div class="base-url-pill" id="base-url-pill" title="Click to copy Base URL" style="cursor: pointer;">
                <span>Base URL:</span>
                <strong id="base-url-text">{{ url('/') }}/</strong>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
            </div>
        </div>

        <!-- Rendered Markdown Output -->
        <article id="content">
            <!-- Content will be injected by marked.js -->
        </article>
    </main>

    <!-- Back to Top Button -->
    <button id="btn-back-top" title="Back to top">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="19" x2="12" y2="5"></line>
            <polyline points="5 12 12 5 19 12"></polyline>
        </svg>
    </button>

    <!-- Raw Markdown Data Container -->
    <script id="raw-markdown" type="text/markdown">
{!! $markdown !!}
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rawMarkdown = document.getElementById('raw-markdown').textContent;
            const contentDiv = document.getElementById('content');
            const navTree = document.getElementById('nav-tree');

            // Configure marked with highlight.js
            marked.setOptions({
                highlight: function(code, lang) {
                    const language = highlight.getLanguage(lang) ? lang : 'plaintext';
                    return highlight.highlight(code, { language }).value;
                },
                gfm: true,
                breaks: false,
            });

            // Render Markdown to HTML
            contentDiv.innerHTML = marked.parse(rawMarkdown);

            // Enhance pre blocks with copy buttons
            document.querySelectorAll('#content pre').forEach((pre) => {
                const button = document.createElement('button');
                button.className = 'copy-btn';
                button.innerText = 'Copy';
                button.addEventListener('click', () => {
                    const codeText = pre.querySelector('code').innerText;
                    navigator.clipboard.writeText(codeText).then(() => {
                        button.innerText = 'Copied!';
                        button.style.backgroundColor = '#10b981';
                        button.style.color = '#ffffff';
                        setTimeout(() => {
                            button.innerText = 'Copy';
                            button.style.backgroundColor = '';
                            button.style.color = '';
                        }, 2000);
                    });
                });
                pre.appendChild(button);
            });

            // Convert inline HTTP methods in headers or paragraphs to colored badges
            document.querySelectorAll('#content h3, #content li, #content p').forEach(el => {
                let html = el.innerHTML;
                html = html.replace(/\b(GET)\b/g, '<span class="badge-method badge-get">GET</span>');
                html = html.replace(/\b(POST)\b/g, '<span class="badge-method badge-post">POST</span>');
                html = html.replace(/\b(PUT)\b/g, '<span class="badge-method badge-put">PUT</span>');
                html = html.replace(/\b(DELETE)\b/g, '<span class="badge-method badge-delete">DELETE</span>');
                el.innerHTML = html;
            });

            // Generate Sidebar Navigation from Headings
            const headings = contentDiv.querySelectorAll('h2, h3');
            let navHtml = '';

            headings.forEach((h, index) => {
                // Assign an ID if not present
                if (!h.id) {
                    h.id = 'sec-' + h.innerText.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '') || ('sec-' + index);
                }

                const isH2 = h.tagName.toLowerCase() === 'h2';
                const levelClass = isH2 ? 'level-2' : 'level-3';
                const text = h.innerText.replace(/^(GET|POST|PUT|DELETE)\s+/i, '').trim();

                navHtml += `<a href="#${h.id}" class="nav-item ${levelClass}" data-title="${text.toLowerCase()}">${text}</a>`;
            });

            navTree.innerHTML = navHtml;

            // Search Filter
            const searchInput = document.getElementById('search-input');
            searchInput.addEventListener('input', (e) => {
                const val = e.target.value.toLowerCase().trim();
                const navItems = navTree.querySelectorAll('.nav-item');

                navItems.forEach(item => {
                    const title = item.getAttribute('data-title') || item.innerText.toLowerCase();
                    if (!val || title.includes(val)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // Active Section Highlight on Scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.id;
                        document.querySelectorAll('.nav-item').forEach(item => {
                            if (item.getAttribute('href') === `#${id}`) {
                                item.classList.add('active');
                            } else {
                                item.classList.remove('active');
                            }
                        });
                    }
                });
            }, { rootMargin: '-10% 0px -80% 0px' });

            headings.forEach(h => observer.observe(h));

            // Copy Base URL on Click
            const baseUrlPill = document.getElementById('base-url-pill');
            baseUrlPill.addEventListener('click', () => {
                const urlText = document.getElementById('base-url-text').innerText;
                navigator.clipboard.writeText(urlText).then(() => {
                    baseUrlPill.style.borderColor = '#10b981';
                    const originalSpan = baseUrlPill.querySelector('span').innerText;
                    baseUrlPill.querySelector('span').innerText = 'Copied!';
                    setTimeout(() => {
                        baseUrlPill.style.borderColor = '';
                        baseUrlPill.querySelector('span').innerText = originalSpan;
                    }, 2000);
                });
            });

            // Mobile Navigation Toggle
            const mobileToggle = document.getElementById('mobile-toggle');
            const sidebar = document.getElementById('sidebar');

            mobileToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });

            // Close mobile sidebar on nav click
            navTree.addEventListener('click', (e) => {
                if (e.target.classList.contains('nav-item') && window.innerWidth <= 1024) {
                    sidebar.classList.remove('open');
                }
            });

            // Back to Top Button
            const backTopBtn = document.getElementById('btn-back-top');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 400) {
                    backTopBtn.classList.add('visible');
                } else {
                    backTopBtn.classList.remove('visible');
                }
            });

            backTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>
