<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class ArticleContentSanitizer
{
    /** @var array<string, list<string>> */
    private const ALLOWED_ELEMENTS = [
        'p' => [], 'br' => [], 'h2' => [], 'h3' => [], 'h4' => [],
        'ul' => [], 'ol' => [], 'li' => [], 'strong' => [], 'b' => [],
        'em' => [], 'i' => [], 'u' => [], 's' => [], 'blockquote' => [],
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height', 'loading'],
        'figure' => [], 'figcaption' => [], 'hr' => [], 'code' => [], 'pre' => [],
        'table' => [], 'thead' => [], 'tbody' => [], 'tr' => [],
        'th' => ['colspan', 'rowspan'], 'td' => ['colspan', 'rowspan'],
    ];

    private const DROP_WITH_CONTENT = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'svg', 'math'];

    public function sanitize(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="article-content-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('article-content-root');

        if (! $root) {
            return '';
        }

        $this->cleanChildren($root);

        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $document->saveHTML($child);
        }

        return trim($result);
    }

    public function fromPlainText(?string $text): string
    {
        $text = trim((string) $text);

        if ($text === '') {
            return '';
        }

        $paragraphs = preg_split('/\R{2,}/u', $text) ?: [];

        return collect($paragraphs)
            ->map(fn (string $paragraph): string => '<p>'.nl2br(e(trim($paragraph)), false).'</p>')
            ->implode("\n");
    }

    private function cleanChildren(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($node->tagName);

            if (in_array($tag, self::DROP_WITH_CONTENT, true)) {
                $parent->removeChild($node);

                continue;
            }

            if (! array_key_exists($tag, self::ALLOWED_ELEMENTS)) {
                $this->unwrap($node);

                continue;
            }

            $this->cleanAttributes($node, self::ALLOWED_ELEMENTS[$tag]);
            $this->cleanChildren($node);
        }
    }

    /** @param list<string> $allowed */
    private function cleanAttributes(DOMElement $element, array $allowed): void
    {
        foreach (iterator_to_array($element->attributes) as $attribute) {
            if (! in_array(strtolower($attribute->name), $allowed, true)) {
                $element->removeAttribute($attribute->name);
            }
        }

        if ($element->tagName === 'a' && $element->hasAttribute('href')) {
            if (! $this->isSafeUrl($element->getAttribute('href'), true)) {
                $element->removeAttribute('href');
            }

            if ($element->getAttribute('target') === '_blank') {
                $element->setAttribute('rel', 'noopener noreferrer');
            } else {
                $element->removeAttribute('target');
            }
        }

        if ($element->tagName === 'img') {
            if (! $this->isSafeUrl($element->getAttribute('src'), false)) {
                $element->parentNode?->removeChild($element);

                return;
            }

            $element->setAttribute('loading', 'lazy');
        }
    }

    private function isSafeUrl(string $url, bool $allowMailto): bool
    {
        $url = trim($url);

        if ($url === '' || str_starts_with($url, '/') || str_starts_with($url, '#')) {
            return true;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return in_array($scheme, $allowMailto ? ['http', 'https', 'mailto'] : ['http', 'https'], true);
    }

    private function unwrap(DOMElement $element): void
    {
        $parent = $element->parentNode;

        if (! $parent) {
            return;
        }

        while ($element->firstChild) {
            $parent->insertBefore($element->firstChild, $element);
        }

        $parent->removeChild($element);
        $this->cleanChildren($parent);
    }
}
