<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use App\Support\ArticleContentSanitizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleEditorialWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_html_sanitizer_removes_executable_markup_and_unsafe_urls(): void
    {
        $sanitizer = app(ArticleContentSanitizer::class);

        $clean = $sanitizer->sanitize(<<<'HTML'
            <p onclick="alert(1)">Konten <strong>aman</strong></p>
            <script>alert('xss')</script>
            <a href="javascript:alert(1)" target="_blank">Tautan</a>
            <img src="data:text/html;base64,WA==" onerror="alert(1)">
            <a href="https://example.com" target="_blank">Eksternal</a>
            HTML);

        $this->assertStringContainsString('<p>Konten <strong>aman</strong></p>', $clean);
        $this->assertStringNotContainsString('script', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('javascript:', $clean);
        $this->assertStringNotContainsString('data:', $clean);
        $this->assertStringContainsString('rel="noopener noreferrer"', $clean);
    }

    public function test_plain_text_mode_escapes_html_and_creates_paragraphs(): void
    {
        $html = app(ArticleContentSanitizer::class)->fromPlainText(
            "Paragraf pertama <script>alert(1)</script>\n\nParagraf kedua"
        );

        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('<p>Paragraf pertama', $html);
        $this->assertStringContainsString('<p>Paragraf kedua</p>', $html);
        $this->assertStringNotContainsString('<script>', $html);
    }

    public function test_article_workflow_controls_public_visibility(): void
    {
        [$author, $category] = $this->editorialDependencies();

        $draft = Article::create($this->articleData($author, $category, 'draft'));
        $scheduled = Article::create(array_merge(
            $this->articleData($author, $category, 'scheduled'),
            ['slug' => 'scheduled-article', 'published_at' => now()->addHour()]
        ));
        $published = Article::create(array_merge(
            $this->articleData($author, $category, 'published'),
            ['slug' => 'published-article', 'published_at' => now()->subMinute()]
        ));

        $visibleIds = Article::published()->pluck('id');

        $this->assertFalse($visibleIds->contains($draft->id));
        $this->assertFalse($visibleIds->contains($scheduled->id));
        $this->assertTrue($visibleIds->contains($published->id));
    }

    public function test_article_update_increments_revision_and_sanitizes_content(): void
    {
        [$author, $category] = $this->editorialDependencies();
        $article = Article::create($this->articleData($author, $category, 'draft'));

        $article->update([
            'content' => '<p>Versi baru</p><script>alert(1)</script>',
        ]);

        $this->assertSame(2, $article->fresh()->revision);
        $this->assertSame(2, $article->fresh()->lock_version);
        $this->assertStringNotContainsString('<script>', $article->fresh()->content);
        $this->assertSame(1, $article->fresh()->reading_time);
    }

    public function test_article_auto_generates_seo_metadata_if_empty(): void
    {
        [$author, $category] = $this->editorialDependencies();

        $article = Article::create(array_merge(
            $this->articleData($author, $category, 'draft'),
            [
                'title' => 'Judul Kopi Panama Terlezat',
                'excerpt' => 'Ini ringkasan kopi panama yang lezat sekali.',
                'meta_title' => null,
                'meta_description' => null,
            ]
        ));

        $this->assertSame('Judul Kopi Panama Terlezat', $article->fresh()->meta_title);
        $this->assertSame('Ini ringkasan kopi panama yang lezat sekali.', $article->fresh()->meta_description);
    }

    /** @return array{User, ArticleCategory} */
    private function editorialDependencies(): array
    {
        $author = User::factory()->create();
        $category = ArticleCategory::create([
            'name' => 'Edukasi',
            'slug' => 'edukasi',
        ]);

        return [$author, $category];
    }

    /** @return array<string, mixed> */
    private function articleData(User $author, ArticleCategory $category, string $workflow): array
    {
        return [
            'author_id' => $author->id,
            'category_id' => $category->id,
            'title' => 'Artikel Editorial',
            'slug' => 'artikel-editorial',
            'content' => '<p>Konten artikel untuk pengujian workflow editorial.</p>',
            'editor_mode' => 'visual',
            'workflow_status' => $workflow,
            'status' => 'draft',
        ];
    }
}
