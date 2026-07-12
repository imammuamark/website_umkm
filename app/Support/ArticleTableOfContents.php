<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Str;

final class ArticleTableOfContents
{
    /**
     * @return array{html: string, items: array<int, array{id: string, title: string, level: int}>}
     */
    public function transform(string $html): array
    {
        if (trim($html) === '' || ! class_exists(DOMDocument::class)) {
            return ['html' => $html, 'items' => []];
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8"><div id="article-content-root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($document);
        $items = [];
        $used = [];

        foreach ($xpath->query('//*[@id="article-content-root"]//*[self::h2 or self::h3]') ?: [] as $heading) {
            if (! $heading instanceof DOMElement) {
                continue;
            }

            $title = trim(preg_replace('/\s+/u', ' ', $heading->textContent) ?? '');
            if ($title === '') {
                continue;
            }

            $base = Str::slug($title) ?: 'bagian';
            $id = $base;
            $suffix = 2;
            while (isset($used[$id])) {
                $id = $base.'-'.$suffix++;
            }

            $used[$id] = true;
            $heading->setAttribute('id', $id);
            $items[] = ['id' => $id, 'title' => $title, 'level' => (int) substr($heading->tagName, 1)];
        }

        $root = $document->getElementById('article-content-root');
        $output = '';
        if ($root) {
            foreach ($root->childNodes as $node) {
                $output .= $document->saveHTML($node);
            }
        }

        return ['html' => $output ?: $html, 'items' => $items];
    }
}
