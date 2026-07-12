<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Support\ArticleContentSanitizer;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->prepareContent($data);
        $data['author_id'] = auth()->id();
        $data['published_by'] = in_array($data['workflow_status'], ['published', 'scheduled'], true)
            ? auth()->id()
            : null;

        $this->authorizeWorkflow($data['workflow_status']);

        return $data;
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan Artikel');
    }

    /** @param array<string, mixed> $data */
    private function prepareContent(array $data): array
    {
        $sanitizer = app(ArticleContentSanitizer::class);
        $mode = $data['editor_mode'] ?? 'visual';

        $data['content'] = match ($mode) {
            'plain' => $sanitizer->fromPlainText($data['content_plain'] ?? ''),
            'html' => $sanitizer->sanitize($data['content_html'] ?? ''),
            default => $sanitizer->sanitize($data['content'] ?? ''),
        };

        unset($data['content_plain'], $data['content_html'], $data['expected_lock_version']);

        return $data;
    }

    private function authorizeWorkflow(string $workflow): void
    {
        if (in_array($workflow, ['scheduled', 'published', 'archived'], true)
            && ! auth()->user()?->can('publish articles')) {
            throw ValidationException::withMessages([
                'workflow_status' => 'Anda tidak memiliki izin untuk memublikasikan atau mengarsipkan artikel.',
            ]);
        }
    }
}
