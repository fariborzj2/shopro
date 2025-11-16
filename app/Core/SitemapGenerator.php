<?php

namespace App\Core;

use App\Models\BlogPost;
use DOMDocument;

class SitemapGenerator
{
    /**
     * Generate the sitemap.xml file.
     *
     * Fetches all published blog posts and creates a sitemap file
     * in the public directory.
     *
     * @return bool Returns true on success, false on failure.
     */
    public static function generate()
    {
        $posts = BlogPost::getAllPublished();
        $site_url = self::getBaseUrl();

        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;

        $urlset = $xml->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset');
        $xml->appendChild($urlset);

        foreach ($posts as $post) {
            $url = $xml->createElement('url');
            $urlset->appendChild($url);

            // <loc>
            $loc = $xml->createElement('loc', $site_url . '/blog/' . htmlspecialchars($post['slug']));
            $url->appendChild($loc);

            // <lastmod>
            $lastmod_date = !empty($post['updated_at']) ? $post['updated_at'] : $post['created_at'];
            $lastmod = $xml->createElement('lastmod', date('Y-m-d\TH:i:sP', strtotime($lastmod_date)));
            $url->appendChild($lastmod);
        }

        $sitemap_path = __DIR__ . '/../../public/sitemap.xml';

        if ($xml->save($sitemap_path)) {
            return true;
        }

        return false;
    }

    /**
     * Get the base URL of the site.
     *
     * @return string
     */
    private static function getBaseUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocol . '://' . $host;
    }
}
