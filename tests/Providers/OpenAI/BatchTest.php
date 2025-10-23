<?php

declare(strict_types=1);

namespace Tests\Providers\OpenAI;

use Error;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Exceptions\PrismException;
use Prism\Prism\Prism;
use Tests\Fixtures\FixtureResponse;

beforeEach(function (): void {
    config()->set('prism.providers.openai.api_key', env('OPENAI_API_KEY'));
});

it('PrismException is thrown when batchFileId is not set', function (): void {
    expect(fn (): \Prism\Prism\Batch\Response => Prism::batch()
        ->using(Provider::OpenAI, 'gpt-4.1')
        ->withBatchFileId('')
        ->retrieveBatch())->toThrow(PrismException::class);
});

it('can retrieve a batch', function (): void {
    FixtureResponse::fakeResponseSequence('https://api.openai.com/v1/batches/batch_abc123', 'openai/batch-retrieve-succesful-batch-response');

    $retrievedBatch =
    Prism::batch()
        ->using(Provider::OpenAI, 'gpt-4.1')
        ->withBatchFileId('batch_abc123')
        ->retrieveBatch();

    expect($retrievedBatch->providerSpecificData['id'])->toBe('batch_abc123');
    expect($retrievedBatch->providerSpecificData['status'])->toBe('completed');
    expect($retrievedBatch->providerSpecificData['object'])->toBe('batch');

    Http::assertSent(fn (Request $request): bool => $request->url() === 'https://api.openai.com/v1/batches/batch_abc123'
    );
});

it('can start processing of a batch', function (): void {
    FixtureResponse::fakeResponseSequence('https://api.openai.com/v1/batches', 'openai/batch-start-processing');

    $processBatch = Prism::batch()
        ->using(Provider::OpenAI, 'gpt-4.1')
        ->withFileInputId('file-abc123')
        ->withBatchFileId('batch_abc123')
        ->processBatch();

    expect($processBatch->providerSpecificData['id'])->toBe('batch_abc123');
    expect($processBatch->providerSpecificData['status'])->toBe('validating');

    Http::assertSent(fn (Request $request): bool => $request->url() === 'https://api.openai.com/v1/batches'
    );
});

it('Error is thrown when fileInputId is not set for processBatch', function (): void {
    expect(fn (): \Prism\Prism\Batch\Response => Prism::batch()
        ->using(Provider::OpenAI, 'gpt-4.1')
        ->withBatchFileId('batch_abc123')
        ->processBatch())->toThrow(Error::class);
});

it('PrismException is thrown when fileInputId is set to empty string for processBatch', function (): void {
    expect(fn (): \Prism\Prism\Batch\Response => Prism::batch()
        ->using(Provider::OpenAI, 'gpt-4.1')
        ->withBatchFileId('batch_abc123')
        ->withFileInputId('')
        ->processBatch())->toThrow(PrismException::class);
});
