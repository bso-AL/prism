<?php

use Prism\Prism\Enums\Provider;
use Prism\Prism\Exceptions\PrismException;
use Prism\Prism\Prism;
use Prism\Prism\Providers\OpenAI\Enums\BatchEndPointsEnum;
use Tests\Fixtures\FixtureResponse;

beforeEach(function () {
    config()->set('prism.providers.openai.api_key', env('OPENAI_API_KEY') ?? '123');
    $this->provider = Prism::provider(Provider::OpenAI);
});

it('can retrieve a batch', function () {
    FixtureResponse::fakeResponseSequence('https://api.openai.com/v1/batches/*', 'openai/batch-retrieve-batch');

    $batchToRetrieve = $this->provider->batch()->withBatchFileId('batch_123')->retrieveBatch();

    expect($batchToRetrieve->providerSpecificData['id'])->toBe('batch_123');
    expect($batchToRetrieve->providerSpecificData['output_file_id'])->toBe('file-12345FGHIJ');
})->only();

it('can list all batch files', function () {
    FixtureResponse::fakeResponseSequence('https://api.openai.com/v1/batches', 'openai/batch-list-all-batches');

    $listBatches = $this->provider->batch()->listBatches();

    expect($listBatches->providerSpecificData['data'][0]->providerSpecificData['id'])->toBe('batch_123');
    expect($listBatches->providerSpecificData['data'][0]->providerSpecificData['output_file_id'])->toBe('file-987987');
    expect($listBatches->providerSpecificData['data'][1]->providerSpecificData['id'])->toBe('batch_M456');
    expect($listBatches->providerSpecificData['data'][1]->providerSpecificData['output_file_id'])->toBe('file-A123s123');
    expect($listBatches->providerSpecificData['first_id'])->toBe('batch_123');
    expect($listBatches->providerSpecificData['last_id'])->toBe('batch_M456');
    expect($listBatches->providerSpecificData['has_more'])->toBe(false);
});

it('can process a batch file', function () {
    FixtureResponse::fakeResponseSequence('https://api.openai.com/v1/batches', 'openai/batch-process-batch');

    $processBatch = $this->provider->batch()
        ->withFileInputId('file-INPUT12345')
        ->withEndPoint(BatchEndPointsEnum::ChatCompletions)
        ->processBatch();

    expect($processBatch->providerSpecificData['id'])->toBe('batch_abc123');
    expect($processBatch->providerSpecificData['input_file_id'])->toBe('file-abc123');
});

it('throws an exception when processing a batch with empty file input id', function () {
    $this->provider->batch()
        ->withEndPoint(BatchEndPointsEnum::Completions)
        ->processBatch();
})->throws(PrismException::class, 'File input ID is required for batch processing.');

it('throws an exception when retrieveBatch is not supplied a batch file id', function () {
    $this->provider->batch()
        ->retrieveBatch();
})->throws(PrismException::class, 'Batch ID is required for batch retrieval.');
