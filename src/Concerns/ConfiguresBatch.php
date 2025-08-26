<?php

declare(strict_types=1);

namespace Prism\Prism\Concerns;

use Prism\Prism\Enums\BatchEndPoints;

trait ConfiguresBatch
{
    private string $fileInputId;

    private string $endPoint;

    private string $completionWindow = '24h';

    private string $batchFileId;

    public function withEndpoint(BatchEndPoints $endPoint): self
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
