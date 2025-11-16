<?php

namespace App\Core;

class Paginator
{
    public $total_items;
    public $items_per_page;
    public $current_page;
    public $total_pages;
    public $base_url;

    /**
     * Paginator constructor.
     *
     * @param int $total_items Total number of items.
     * @param int $items_per_page Number of items to display per page.
     * @param int $current_page The current page number.
     * @param string $base_url The base URL for pagination links.
     */
    public function __construct($total_items, $items_per_page = 10, $current_page = 1, $base_url = '')
    {
        $this->total_items = (int) $total_items;
        $this->items_per_page = (int) $items_per_page;
        $this->current_page = (int) $current_page > 0 ? (int) $current_page : 1;
        $this->base_url = $base_url;

        $this->total_pages = (int) ceil($this->total_items / $this->items_per_page);

        if ($this->current_page > $this->total_pages && $this->total_pages > 0) {
            $this->current_page = $this->total_pages;
        } elseif ($this->current_page < 1) {
            $this->current_page = 1;
        }
    }

    /**
     * Get the offset for the database query.
     *
     * @return int
     */
    public function getOffset()
    {
        return ($this->current_page - 1) * $this->items_per_page;
    }

    /**
     * Check if there is a previous page.
     *
     * @return bool
     */
    public function hasPrev()
    {
        return $this->current_page > 1;
    }

    /**
     * Check if there is a next page.
     *
     * @return bool
     */
    public function hasNext()
    {
        return $this->current_page < $this->total_pages;
    }

    /**
     * Get the URL for the previous page.
     *
     * @return string
     */
    public function getPrevUrl()
    {
        return $this->hasPrev() ? $this->buildUrl($this->current_page - 1) : '#';
    }

    /**
     * Get the URL for the next page.
     *
     * @return string
     */
    public function getNextUrl()
    {
        return $this->hasNext() ? $this->buildUrl($this->current_page + 1) : '#';
    }

    /**
     * Build the URL for a specific page.
     *
     * @param int $page
     * @return string
     */
    public function buildUrl($page)
    {
        $url = $this->base_url;
        if ($url !== '/') {
            $url = rtrim($url, '/');
        }
        $separator = strpos($url, '?') === false ? '?' : '&';
        return $url . $separator . 'page=' . $page;
    }
}
