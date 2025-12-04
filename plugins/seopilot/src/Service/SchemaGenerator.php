<?php

namespace SeoPilot\Enterprise\Service;

use App\Models\BlogPost;
use App\Models\Product;
use App\Models\Category;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\Page;
use App\Models\FaqItem;
use App\Models\Review;
use App\Core\Database;

class SchemaGenerator
{
    /**
     * Generate comprehensive Schema Data based on context.
     *
     * @param array $context ['type' => '...', 'id' => ..., 'slug' => ...]
     * @return array
     */
    public static function generate(array $context): array
    {
        $schema = [];
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $currentUrl = $url . ($_SERVER['REQUEST_URI'] ?? '/');

        // 1. BreadcrumbList (Universal)
        $breadcrumb = self::generateBreadcrumb($context, $url);
        if ($breadcrumb) {
            $schema[] = $breadcrumb;
        }

        // 2. Specific Entity Schema
        switch ($context['type']) {
            case 'home':
                $schema[] = self::generateWebSite($url);
                $schema[] = self::generateOrganization($url);
                break;

            case 'product':
                if ($context['id']) {
                    $product = Product::find($context['id']);
                    if ($product) {
                        $schema[] = self::generateProduct($product, $currentUrl);
                    }
                }
                break;

            case 'post':
                if ($context['id']) {
                    $post = BlogPost::findByIdWithCategory($context['id']);
                    if ($post) {
                        $schema[] = self::generateArticle($post, $currentUrl);
                        // If post has FAQs
                        if (!empty($post->faq)) {
                            $faqItems = json_decode($post->faq, true);
                            if (is_array($faqItems) && !empty($faqItems)) {
                                $schema[] = self::generateFAQPage($faqItems, $currentUrl);
                            }
                        }
                    }
                }
                break;

            case 'category':
                if ($context['slug']) {
                    // Check Product Category first
                    $cat = Category::findBy('slug', $context['slug']);
                    if ($cat) {
                         $products = Product::findAllBy('category_id', $cat->id, 'position ASC');
                         $schema[] = self::generateCollectionPage($cat, $products, $currentUrl, 'Product');
                    } else {
                        // Try Blog Category
                         $cat = BlogCategory::findBy('slug', $context['slug']);
                         if ($cat) {
                              // We don't have easy access to posts without re-querying, but let's do a lightweight query or just Page schema
                              // Ideally we query a few posts
                              $schema[] = self::generateCollectionPage($cat, [], $currentUrl, 'BlogPosting');
                         }
                    }
                }
                break;

            case 'page':
                if ($context['slug'] === 'faq') {
                     $faqItems = FaqItem::findAllGroupedByType();
                     // Flatten
                     $allFaqs = [];
                     foreach ($faqItems as $group) {
                         foreach ($group as $item) {
                             $allFaqs[] = $item;
                         }
                     }
                     $schema[] = self::generateFAQPage($allFaqs, $currentUrl);
                } elseif ($context['slug']) {
                    $page = Page::findBySlug($context['slug']);
                    if ($page) {
                        $schema[] = self::generateWebPage($page, $currentUrl);
                    }
                }
                break;

            case 'tag':
                 if ($context['slug']) {
                     $tag = BlogTag::findBy('slug', $context['slug']);
                     if ($tag) {
                         $schema[] = self::generateCollectionPage((object)['name' => $tag->name, 'description' => 'Posts tagged with ' . $tag->name], [], $currentUrl, 'BlogPosting');
                     }
                 }
                 break;
        }

        return $schema;
    }

    private static function generateBreadcrumb($context, $baseUrl)
    {
        $items = [];
        $items[] = [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => $baseUrl
        ];

        $position = 2;

        if ($context['type'] === 'product' && $context['id']) {
             $product = Product::find($context['id']);
             if ($product) {
                 // Try to find category
                 if ($product->category_id) {
                      // We need to fetch category name/slug.
                      // Since Product model join doesn't give slug easily in `find`, let's query category.
                      // Optimization: Depending on traffic, caching this would be good.
                      // For now, simple query.
                      $stmt = Database::getConnection()->prepare("SELECT name_fa, slug FROM categories WHERE id = ?");
                      $stmt->execute([$product->category_id]);
                      $cat = $stmt->fetch(\PDO::FETCH_OBJ);
                      if ($cat) {
                          $items[] = [
                              '@type' => 'ListItem',
                              'position' => $position++,
                              'name' => $cat->name_fa,
                              'item' => $baseUrl . '/category/' . $cat->slug
                          ];
                      }
                 }
                 $items[] = [
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => $product->name_fa,
                    'item' => $baseUrl . "/product/{$product->id}-" . self::slugify($product->name_fa) // Approximate URL if slug missing
                 ];
             }
        } elseif ($context['type'] === 'post' && $context['id']) {
             $post = BlogPost::findByIdWithCategory($context['id']);
             if ($post) {
                 if (!empty($post->category_name) && !empty($post->category_slug)) {
                      $items[] = [
                          '@type' => 'ListItem',
                          'position' => $position++,
                          'name' => $post->category_name,
                          'item' => $baseUrl . '/blog/category/' . $post->category_slug
                      ];
                 }
                 $items[] = [
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => $post->title,
                    'item' => $baseUrl . "/blog/" . ($post->category_slug ?? 'news') . "/{$post->id}-{$post->slug}"
                 ];
             }
        } elseif ($context['type'] === 'category' && $context['slug']) {
            // Check if Product Category
            $cat = Category::findBy('slug', $context['slug']);
            if (!$cat) $cat = BlogCategory::findBy('slug', $context['slug']);

            if ($cat) {
                 $items[] = [
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => $cat->name_fa,
                    'item' => $baseUrl . ($_SERVER['REQUEST_URI'])
                 ];
            }
        } elseif ($context['type'] === 'page' && $context['slug']) {
             $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => ucfirst($context['slug']), // Placeholder name if page object not handy yet
                'item' => $baseUrl . '/page/' . $context['slug']
             ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items
        ];
    }

    private static function generateWebSite($url)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'فروشگاه مدرن', // Should come from Settings preferably
            'url' => $url,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $url . '/blog?search={search_term_string}',
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }

    private static function generateOrganization($url)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'فروشگاه مدرن',
            'url' => $url,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $url . '/assets/logo.png' // Placeholder/Default
            ]
        ];
    }

    private static function generateProduct($product, $url)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name_fa,
            'description' => strip_tags($product->description ?? ''),
            'image' => $product->image_url ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : $url . $product->image_url) : null,
            'sku' => (string)$product->id,
            'offers' => [
                '@type' => 'Offer',
                'url' => $url,
                'priceCurrency' => 'IRR', // Assuming Toman/Rial, using IRR is standard for schema even if Toman displayed
                'price' => $product->price,
                'availability' => ($product->status === 'available') ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            ]
        ];

        // Average Rating
        // Need to fetch reviews or aggregated data.
        // Product model in `paginated` has no rating. Review model needed.
        // Optimization: Don't fetch all reviews.
        // Let's assume we can get average if needed, or skip if expensive.
        // For now, let's try to get simple count/avg if possible or skip.
        // The user wants "intelligent", so we should try.
        $reviews = Review::findByProductId($product->id);
        if (!empty($reviews)) {
            $count = count($reviews);
            $sum = array_reduce($reviews, function($carry, $item){ return $carry + $item['rating']; }, 0);
            $avg = $count > 0 ? round($sum / $count, 1) : 0;

            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $avg,
                'reviewCount' => $count
            ];

            // Add top review
            $schema['review'] = [
                '@type' => 'Review',
                'reviewRating' => [
                    '@type' => 'Rating',
                    'ratingValue' => $reviews[0]['rating']
                ],
                'author' => [
                    '@type' => 'Person',
                    'name' => $reviews[0]['user_name']
                ],
                'reviewBody' => $reviews[0]['comment']
            ];
        }

        return $schema;
    }

    private static function generateArticle($post, $url)
    {
        $base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'image' => $post->image_url ? (strpos($post->image_url, 'http') === 0 ? $post->image_url : $base_url . $post->image_url) : null,
            'author' => [
                '@type' => 'Person',
                'name' => $post->author_name ?? 'Admin'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'فروشگاه مدرن',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $base_url . '/assets/logo.png'
                ]
            ],
            'datePublished' => date('c', strtotime($post->published_at ?? $post->created_at ?? 'now')),
            'dateModified' => date('c', strtotime($post->updated_at ?? $post->published_at ?? $post->created_at ?? 'now')),
            'description' => $post->excerpt ?? mb_substr(strip_tags($post->content), 0, 160),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $url
            ]
        ];
    }

    private static function generateFAQPage($items, $url)
    {
        $questions = [];
        foreach ($items as $item) {
            // Check if item is array (from raw JSON) or object (from DB model)
            $q = is_array($item) ? ($item['question'] ?? '') : ($item->question ?? '');
            $a = is_array($item) ? ($item['answer'] ?? '') : ($item->answer ?? '');

            if ($q && $a) {
                $questions[] = [
                    '@type' => 'Question',
                    'name' => $q,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $a
                    ]
                ];
            }
        }

        if (empty($questions)) return null;

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $questions
        ];
    }

    private static function generateCollectionPage($category, $items, $url, $itemType)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $category->name_fa ?? $category->name ?? 'Category',
            'description' => strip_tags($category->description ?? ''),
            'url' => $url
        ];

        // ItemList
        if (!empty($items)) {
            $list = [];
            foreach ($items as $index => $item) {
                $list[] = [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'url' => $url // Ideal would be item specific URL
                ];
                // Note: deeply nesting full product/post entities inside CollectionPage can be heavy.
                // Google recommends ItemList with URLs.
            }
            $schema['mainEntity'] = [
                '@type' => 'ItemList',
                'itemListElement' => $list
            ];
        }

        return $schema;
    }

    private static function generateWebPage($page, $url)
    {
         return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $page->title,
            'url' => $url,
            'description' => mb_substr(strip_tags($page->content), 0, 160)
         ];
    }

    private static function slugify($text)
    {
        // Simple slugify for fallback
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));
    }
}
