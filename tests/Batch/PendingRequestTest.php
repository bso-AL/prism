<?php

declare(strict_types=1);

use Prism\Prism\Batch\PendingRequest;
use Prism\Prism\Enums\BatchEndPoints;
use Prism\Prism\Enums\Provider;

beforeEach(function (): void {
    $this->pendingRequest = new PendingRequest;
});

it('can configure batch endpoints', function (): void {

    $this->pendingRequest->using(Provider::OpenAI, 'gpt-4.1')->withEndpoint(BatchEndPoints::OPENAI_CHAT_COMPLETIONS);

    expect($this->pendingRequest->toRequest()->endPoint())->toBe(BatchEndPoints::OPENAI_CHAT_COMPLETIONS->value);
});

it('can configure batch file id', function (): void {
    $this->pendingRequest->using(Provider::OpenAI, 'gpt-4.1')->withBatchFileId('file-123');

    expect($this->pendingRequest->toRequest()->batchFileId())->toBe('file-123');
});

it('can configure file input id', function (): void {
    $this->pendingRequest->using(Provider::OpenAI, 'gpt-4.1')->withFileInputId('file-456');

    expect($this->pendingRequest->toRequest()->fileInputId())->toBe('file-456');
});

it('can configure completion window', function (): void {
    $this->pendingRequest->using(Provider::OpenAI, 'gpt-4.1')->withCompletionWindow('24h');

    expect($this->pendingRequest->toRequest()->completionWindow())->toBe('24h');
});
