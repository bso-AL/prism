<?php

declare(strict_types=1);

namespace Prism\Prism\Providers\OpenAI\Concerns;

use Prism\Prism\Providers\OpenAI\Enums\BatchEndPointsEnum;

trait ConfiguresBatch
{
    private string $fileInputId = '';

    private string $endPoint = '';

    private string $completionWindow = '';

    private string $batchFileId = '';

    private function initializeDefaultConfiguresBatchTrait(): void
    {
        $this->completionWindow = config('prism.open_ai_batch.completion_window', '24h');
        $this->endPoint = BatchEndPointsEnum::ChatCompletions->value;

    }

    public function withEndpoint(BatchEndPointsEnum $endPoint): self
    {
        $this->endPoint = $endPoint->value;

        return $this;
    }

    public function withCompletionWindow(string $completionWindow): self
    {
        $this->completionWindow = $completionWindow;

        return $this;
    }

    public function withBatchFileId(string $batchFileId): self
    {
        $this->batchFileId = $batchFileId;

        return $this;
    }

    public function withFileInputId(string $fileInputId): self
    {
        $this->fileInputId = $fileInputId;

        return $this;
    }
}
