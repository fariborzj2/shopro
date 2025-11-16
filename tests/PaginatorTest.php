<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Core/Paginator.php';

class PaginatorTest extends TestCase
{
    public function testBuildUrlWithExistingQueryParameters()
    {
        $paginator = new App\Core\Paginator(100, 10, 1, '/search?q=foo');
        $this->assertEquals('/search?q=foo&page=2', $paginator->buildUrl(2));
    }

    public function testBuildUrlWithRootUrl()
    {
        $paginator = new App\Core\Paginator(100, 10, 1, '/');
        $this->assertEquals('/?page=2', $paginator->buildUrl(2));
    }
}
