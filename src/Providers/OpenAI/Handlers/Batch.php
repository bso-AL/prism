<?php

declare(strict_types=1);

namespace Prism\Prism\Providers\OpenAI\Handlers;

use Illuminate\Http\Client\PendingRequest;
use Prism\Prism\Batch\ListResponse;
use Prism\Prism\Batch\Request;
use Prism\Prism\Batch\Response;
use Prism\Prism\Exceptions\PrismException;
use Prism\Prism\Providers\OpenAI\Concerns\PreparesBatchResponses;
use Prism\Prism\Providers\OpenAI\Concerns\ValidatesResponse;

class Batch
{
    use PreparesBatchResponses;
    use ValidatesResponse;

    public function __construct(protected PendingRequest $client) {}

    public function processBatch(Request $request): Response
    {

        $response = $this->client->post(config('prism.providers.openai.batches_endpoint'), [
            'endpoint' => $request->endPoint(),
            'completion_window' => $request->completionWindow(),
            'input_file_id' => $request->fileInputId(),
        ]);

        $this->validateResponse($response);
        if ($response->status() !== 200) {
            throw new PrismException('Failed to process batch');
        }

        return $this->prepareBatchResponse($response->json());
    }

    public function retrieveBatch(Request $request): Response
    {
        $response = $this->client->get(config('prism.providers.openai.batches_endpoint')."/{$request->batchFileId()}");
        $this->validateResponse($response);

        return $this->prepareBatchResponse($response->json());
    }

    public function cancelBatch(Request $request): Response
    {
        $response = $this->client->post(config('prism.providers.openai.batches_endpoint')."/{$request->batchFileId()}/cancel");
        $this->validateResponse($response);
        if ($response->status() !== 200) {
            throw new PrismException('Failed to cancel batch');
        }

        return $this->prepareBatchResponse($response->json());
    }

    public function listBatches(): ListResponse
    {
        $response = $this->client->get(config('prism.providers.openai.batches_endpoint'));
        $this->validateResponse($response);

        return $this->prepareBatchListResponse($response->json());
    }
}
