<?php

use PHPUnit\Framework\TestCase;
use SeoPilot\Enterprise\NLP\PersianProcessor;
use SeoPilot\Enterprise\Analyzer\PixelAnalyzer;
use SeoPilot\Enterprise\Service\AutoFixer;
use SeoPilot\Enterprise\Injector\BufferInjector;
use SeoPilot\Enterprise\Cache\CacheManager;
use SeoPilot\Enterprise\Security\OutputSanitizer;

class SeoPilotTest extends TestCase
{
    /**
     * Test Case 1: Persian NLP Engine
     * Accepts "messy" input and asserts normalized Persian text.
     */
    public function testPersianNormalization()
    {
        // "موبایل" with Arabic Yeh (ي) and Kaf (ك)
        $messyInput = "موبايل هاي هوشمند"; // Arabic Yeh used
        $expected = "موبایل های هوشمند"; // Persian Yeh

        $normalized = PersianProcessor::normalize($messyInput);

        // Assert Yeh is fixed
        $this->assertEquals($expected, $normalized);

        // Assert numbers are converted to English for URL safety if needed,
        // or just test the number converter specifically.
        $persianNums = "۱۲۳";
        $englishNums = PersianProcessor::toEnglishNumbers($persianNums);
        $this->assertEquals("123", $englishNums);
    }

    /**
     * Test Case 2: PixelAnalyzer
     * Calculates pixel width (not char count) and flags > 580px.
     */
    public function testPixelWidthAnalysis()
    {
        // 'W' is wide (approx 13px), 'i' is narrow (approx 4px).
        // 50 'W's => 50 * 13 = 650px > 580px
        $wideTitle = str_repeat("W", 50);

        $width = PixelAnalyzer::calculateWidth($wideTitle);
        $this->assertGreaterThan(580, $width);

        // 10 'i's => 10 * 4 = 40px < 580px
        $narrowTitle = str_repeat("i", 10);
        $widthNarrow = PixelAnalyzer::calculateWidth($narrowTitle);
        $this->assertLessThan(580, $widthNarrow);
    }

    /**
     * Test Case 3: AutoFixer Fallback
     * Meta description is empty -> extract first 150 chars of content.
     */
    public function testAutoFixerFallback()
    {
        $content = "<div><p>This is a long paragraph that should be used for the meta description. It has enough text to simulate the fallback mechanism correctly. We expect this to be truncated around 150 chars...</p></div>";
        $emptyMeta = ['title' => 'Some Title', 'description' => ''];

        // Mocking AutoFixer behavior (refactored class)
        $fixedMeta = AutoFixer::fix($emptyMeta, $content);

        $this->assertNotEmpty($fixedMeta['description']);
        $this->assertStringContainsString("This is a long paragraph", $fixedMeta['description']);
        $this->assertLessThanOrEqual(160, mb_strlen($fixedMeta['description']));
    }

    /**
     * Test Case 4: Integration & Conflict Testing ("Existing Title")
     * Theme already outputs <title>. BufferInjector should replace, not append.
     */
    public function testBufferInjectorReplacesTitle()
    {
        $html = "<html><head><title>Old Theme Title</title></head><body>Content</body></html>";
        $meta = ['title' => 'New SEO Title', 'description' => 'New Desc'];

        // We assume BufferInjector has a way to inject specific meta for testing
        // without relying on DB (dependency injection or mock).
        // For this test, we might need to modify BufferInjector to accept a data source.

        // Assuming we refactor BufferInjector::inject($html, $metaData)
        $output = BufferInjector::inject($html, $meta);

        // Should contain new title
        $this->assertStringContainsString('<title>New SEO Title</title>', $output);
        // Should NOT contain old title
        $this->assertStringNotContainsString('<title>Old Theme Title</title>', $output);
        // Should have only one title tag
        $this->assertEquals(1, substr_count($output, '<title>'));
    }

    /**
     * Test Case 5: LiteSpeed Headers
     * Assert X-LiteSpeed-Tag is set.
     */
    public function testLiteSpeedHeaders()
    {
        // Mock CacheManager
        $cache = new CacheManager();
        $cache->setTags(['post_123']);

        // Since we can't inspect actual headers sent in PHPUnit easily without running process,
        // we check if the manager *would* send them or stores them in an internal buffer.
        // Or we use xdebug_get_headers if available.
        // For this unit test, we'll verify the internal state of CacheManager.

        $headers = $cache->getPendingHeaders();
        $this->assertArrayHasKey('X-LiteSpeed-Tag', $headers);
        $this->assertEquals('post_123', $headers['X-LiteSpeed-Tag']);
    }

    /**
     * Test Case 6: Redis Failure (Graceful Degradation)
     * Redis timeout -> Switch to File Driver.
     */
    public function testRedisFailureFallback()
    {
        // Mock Redis to throw exception
        $redisMock = $this->getMockBuilder(\Redis::class)
            ->disableOriginalConstructor()
            ->getMock();
        $redisMock->method('get')->willThrowException(new \RedisException("Connection timed out"));

        $manager = new CacheManager($redisMock); // Inject mock

        // Should not throw exception, should return false or fallback value
        $result = $manager->get('some_key');

        // Verify that it degraded (e.g., checked file system or returned null/false)
        // and didn't crash.
        $this->assertNull($result); // Or whatever the fallback return is
    }

    /**
     * Test Case 7: Security (XSS)
     * Inject script in Title -> OutputSanitizer escapes it.
     */
    public function testXSSInjection()
    {
        $maliciousTitle = "<script>alert(1)</script> SEO Title";
        $sanitized = OutputSanitizer::clean($maliciousTitle);

        $this->assertStringNotContainsString('<script>', $sanitized);
        $this->assertEquals('&lt;script&gt;alert(1)&lt;/script&gt; SEO Title', $sanitized);
    }
}
