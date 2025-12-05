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

        // Only inject on Edit/Create pages that likely have TinyMCE
        // Patterns: /admin/posts/create, /admin/posts/edit/1, /admin/products/..., /admin/pages/...
        if (!preg_match('#^/admin/(posts|products|pages|categories)/(create|edit/\d+)#', $uri, $matches)) {
            return;
        }

        // Determine Entity Context
        $entityType = match($matches[1]) {
            'posts' => 'post',
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
        // Note: Since we don't have a formal Hook system in the core, we use ob_start in index.php
        // but here we are called from index.php likely.
        // We will assume `ob_start` callback or we manually inject if we can control the flow.

        // Actually, the plugin entry point `plugins/seopilot/index.php` should register a shutdown function
        // or if the core supports hooks.
        // Given constraints, we'll try to append to the footer if possible,
        // or relying on the BufferInjector to handle *admin* pages too?
        // BufferInjector seems focused on frontend <head>.

        // Let's create a specific output handler for admin injection
        ob_start(function($buffer) use ($entityType, $entityId) {
            return self::injectHtml($buffer, $entityType, $entityId);
        });
    }

    private static function injectHtml($html, $entityType, $entityId)
    {
        // 1. Inject the Analysis Panel (Hidden Modal)
        $panelHtml = file_get_contents(__DIR__ . '/../../views/analysis_panel.php');

        // 2. Inject the Button Script
        // We look for the save button or the editor to place our button nearby.
        // A generic fixed floating button or injecting into the form action bar.
        // Let's try to find `.flex.justify-end` or similar action bar in the form,
        // or just append a floating button for safety.

        // Better: Inject a script that finds the Editor toolbar and appends the button.
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
                        let title = document.querySelector('input[name="title"], input[name="name_fa"]')?.value || '';
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
