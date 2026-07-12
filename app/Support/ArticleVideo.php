<?php

namespace App\Support;

final class ArticleVideo
{
    /**
     * @return array{provider: string, id: string, embed_url: string}|null
     */
    public static function resolve(?string $url): ?array
    {
        if (blank($url) || mb_strlen((string) $url) > 500) {
            return null;
        }

        $parts = parse_url(trim((string) $url));

        if (! is_array($parts) || ($parts['scheme'] ?? null) !== 'https' || blank($parts['host'] ?? null)) {
            return null;
        }

        $host = strtolower(rtrim((string) $parts['host'], '.'));
        $path = trim((string) ($parts['path'] ?? ''), '/');
        $id = null;

        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            if ($path === 'watch') {
                parse_str((string) ($parts['query'] ?? ''), $query);
                $id = $query['v'] ?? null;
            } elseif (preg_match('#^(?:embed|shorts)/([A-Za-z0-9_-]{11})$#', $path, $matches)) {
                $id = $matches[1];
            }

            if (is_string($id) && preg_match('/^[A-Za-z0-9_-]{11}$/', $id)) {
                return [
                    'provider' => 'YouTube',
                    'id' => $id,
                    'embed_url' => "https://www.youtube-nocookie.com/embed/{$id}",
                ];
            }
        }

        if ($host === 'youtu.be' && preg_match('/^([A-Za-z0-9_-]{11})$/', $path, $matches)) {
            return [
                'provider' => 'YouTube',
                'id' => $matches[1],
                'embed_url' => "https://www.youtube-nocookie.com/embed/{$matches[1]}",
            ];
        }

        if (in_array($host, ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'], true)
            && preg_match('#^(?:video/)?([0-9]{6,12})$#', $path, $matches)) {
            return [
                'provider' => 'Vimeo',
                'id' => $matches[1],
                'embed_url' => "https://player.vimeo.com/video/{$matches[1]}",
            ];
        }

        return null;
    }
}
