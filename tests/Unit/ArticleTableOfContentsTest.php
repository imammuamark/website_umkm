<?php

namespace Tests\Unit;

use App\Support\ArticleTableOfContents;
use PHPUnit\Framework\TestCase;

class ArticleTableOfContentsTest extends TestCase
{
    public function test_it_builds_unique_heading_anchors_without_changing_the_copy(): void
    {
        $result = (new ArticleTableOfContents)->transform(
            '<p>Pembuka</p><h2>Persiapan Kopi</h2><h3>Alat &amp; Bahan</h3><h2>Persiapan Kopi</h2>'
        );

        $this->assertSame(['persiapan-kopi', 'alat-bahan', 'persiapan-kopi-2'], array_column($result['items'], 'id'));
        $this->assertStringContainsString('id="persiapan-kopi-2"', $result['html']);
        $this->assertStringContainsString('Alat &amp; Bahan', $result['html']);
    }
}
