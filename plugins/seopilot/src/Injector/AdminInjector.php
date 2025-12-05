<?php

namespace SeoPilot\Enterprise\Injector;

use App\Core\Request;

class AdminInjector
{
    /**
     * Inject SeoPilot scripts and button into Admin Panel
     */
    public static function handle()
    {
        $uri = Request::uri();

        // Debug Log
        // error_log("SeoPilot AdminInjector: Checking URI: $uri");

        // Adjusted Pattern: The core blog routes are /admin/blog/posts/create, not /admin/posts/create
        // Core matches:
        // /admin/blog/posts/create
        // /admin/blog/posts/edit/1
        // /admin/products/create
        // /admin/pages/create

        if (!preg_match('#^/admin/(blog/posts|products|pages|categories)/(create|edit/\d+)#', $uri, $matches)) {
            return;
        }

        // Determine Entity Context
        $entityType = match($matches[1]) {
            'blog/posts' => 'post',
            'products' => 'product',
            'pages' => 'page',
            'categories' => 'category',
            default => 'other'
        };

        $entityId = null;
        if (strpos($matches[2], 'edit/') === 0) {
            $parts = explode('/', $matches[2]);
            $entityId = end($parts);
        }

        // Hook into output buffer
        ob_start(function($buffer) use ($entityType, $entityId) {
            return self::injectHtml($buffer, $entityType, $entityId);
        });
    }

    private static function injectHtml($html, $entityType, $entityId)
    {
        // 1. Inject the Analysis Panel (Hidden Modal)
        $panelHtml = file_get_contents(__DIR__ . '/../../views/analysis_panel.php');

        // 2. Inject the Button Script
        $buttonScript = <<<HTML
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Wait for TinyMCE
                const checkTiny = setInterval(() => {
                    if (window.tinymce && window.tinymce.activeEditor) {
                        clearInterval(checkTiny);
                        addSeoButton();
                    }
                }, 500);

                function addSeoButton() {
                    // Try to find the page header actions or form actions
                    // Common pattern in admin: Heading ... Actions
                    const headerActions = document.querySelector('header .flex.items-center.gap-3')
                                       || document.querySelector('.flex.justify-end.gap-3'); // Fallback

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors shadow-sm ml-2';
                    btn.innerHTML = `
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>آنالیز سئو</span>
                    `;

                    btn.onclick = function(e) {
                        e.preventDefault();
                        // Dispatch event to open Alpine modal
                        // We need the title and slug from the form inputs
                        let title = document.querySelector('input[name="title"]')?.value || document.querySelector('input[name="name_fa"]')?.value || '';
                        let slug = document.querySelector('input[name="slug"]')?.value || '';

                        window.dispatchEvent(new CustomEvent('open-seopilot', {
                            detail: {
                                type: '{$entityType}',
                                id: '{$entityId}',
                                title: title,
                                slug: slug
                            }
                        }));
                    };

                    if (headerActions) {
                        headerActions.prepend(btn);
                    } else {
                        // Fallback: Floating button
                        btn.style.position = 'fixed';
                        btn.style.bottom = '20px';
                        btn.style.left = '20px';
                        btn.style.zIndex = '999';
                        document.body.appendChild(btn);
                    }
                }
            });
        </script>
HTML;

        // Append everything before </body>
        return str_replace('</body>', $panelHtml . $buttonScript . '</body>', $html);
    }
}
