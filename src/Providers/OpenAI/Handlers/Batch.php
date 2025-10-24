<?php

declare(strict_types=1);

namespace Prism\Prism\Providers\OpenAI\Handlers;

use Illuminate\Http\Client\PendingRequest;
use Prism\Prism\Concerns\ConfiguresClient;
use Prism\Prism\Concerns\ConfiguresProviders;
use Prism\Prism\Concerns\HasProviderOptions;
use Prism\Prism\Exceptions\PrismException;
use Prism\Prism\Providers\OpenAI\Batch\ListResponse;
use Prism\Prism\Providers\OpenAI\Batch\Response;
use Prism\Prism\Providers\OpenAI\Concerns\ConfiguresBatch;
use Prism\Prism\Providers\OpenAI\Concerns\ConfiguresFile;
use Prism\Prism\Providers\OpenAI\Concerns\ConfiguresStorage;
use Prism\Prism\Providers\OpenAI\Concerns\PreparesBatchResponses;

class Batch
{
    use ConfiguresBatch;
    use ConfiguresClient;
    use ConfiguresFile;
    use ConfiguresProviders;
    use ConfiguresStorage;
    use HasProviderOptions;
    use PreparesBatchResponses;

    public function __construct(protected PendingRequest $client)
    {
        $this->initializeDefaultConfiguresFileTrait();
        $this->initializeDefaultConfiguresStorageTrait();
        $this->initializeDefaultConfiguresBatchTrait();
    }

    public function processBatch(): Response
    {
        if (! $this->fileInputId || ($this->fileInputId === '' || $this->fileInputId === '0')) {
            throw new PrismException(
                'File input ID is required for batch processing.'
            );
        }
        $response = $this->client->post(config('prism.providers.openai.batches_endpoint'), [
            'endpoint' => $this->endPoint,
            'completion_window' => $this->completionWindow,
            'input_file_id' => $this->fileInputId,
        ]);
        $this->validateResponse($response);
        if ($response->status() !== 200) {
            throw new PrismException('Failed to upload file');
        }

        return $this->prepareBatchResponse($response->json());
    }

    public function retrieveBatch(): Response
    {

        if ($this->batchFileId === '' || $this->batchFileId === '0') {
            throw new PrismException(
                'Batch ID is required for batch retrieval.'
            );
        }
        $response = $this->client->get(config('prism.providers.openai.batches_endpoint')."/{$this->batchFileId}");
        $this->validateResponse($response);

        return $this->prepareBatchResponse($response->json());

    }

    public function listBatches(): ListResponse
    {
        $response = $this->client->get(config('prism.providers.openai.batches_endpoint'));
        $this->validateResponse($response);

        return $this->prepareBatchListResponse($response->json());
    }
}
