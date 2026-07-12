<?php

namespace Tests\Unit;

use App\Support\ArticleVideo;
use PHPUnit\Framework\TestCase;

class ArticleVideoTest extends TestCase
{
    public function test_it_resolves_trusted_youtube_and_vimeo_urls(): void
    {
        $youtube = ArticleVideo::resolve('https://www.youtube.com/watch?v=BY31NstC_t4');
        $vimeo = ArticleVideo::resolve('https://vimeo.com/123456789');

        $this->assertSame('https://www.youtube-nocookie.com/embed/BY31NstC_t4', $youtube['embed_url']);
        $this->assertSame('https://player.vimeo.com/video/123456789', $vimeo['embed_url']);
    }

    public function test_it_rejects_untrusted_or_ambiguous_video_urls(): void
    {
        $this->assertNull(ArticleVideo::resolve('javascript:alert(1)'));
        $this->assertNull(ArticleVideo::resolve('http://youtube.com/watch?v=BY31NstC_t4'));
        $this->assertNull(ArticleVideo::resolve('https://youtube.com.evil.test/watch?v=BY31NstC_t4'));
        $this->assertNull(ArticleVideo::resolve('https://youtu.be/not-valid'));
    }
}
