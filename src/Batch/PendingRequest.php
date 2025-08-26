<?php

declare(strict_types=1);

namespace Prism\Prism\Batch;

use Illuminate\Http\Client\RequestException;
use Prism\Prism\Concerns\ConfiguresBatch;
use Prism\Prism\Concerns\ConfiguresClient;
use Prism\Prism\Concerns\ConfiguresFile;
use Prism\Prism\Concerns\ConfiguresProviders;
use Prism\Prism\Concerns\ConfiguresStorage;
use Prism\Prism\Concerns\HasProviderOptions;
use Prism\Prism\Exceptions\PrismException;

class PendingRequest
{
    use ConfiguresBatch;
    use ConfiguresClient;
    use ConfiguresFile;
    use ConfiguresProviders;
    use ConfiguresStorage;
    use HasProviderOptions;

    public function processBatch(): Response
    {
        if (! $this->fileInputId || ($this->fileInputId === '' || $this->fileInputId === '0')) {
            throw new PrismException(
                'File input ID is required for batch processing.'
            );
        }
        $request = $this->toRequest();

        try {
            return $this->provider->processBatch($request);
        } catch (RequestException $e) {
            $this->provider->handleRequestException((string) $request->model(), $e);
        }
    }

    public function retrieveBatch(): Response
    {
        $request = $this->toRequest();

        if ($this->batchFileId === '' || $this->batchFileId === '0') {
            throw new PrismException(
                'Batch ID is required for batch retrieval.'
            );
        }
        try {
            return $this->provider->retrieveBatch($request);
        } catch (RequestException $e) {
            $this->provider->handleRequestException((string) $request->model(), $e);
        }
    }

    public function listBatches(): ListResponse
    {
        $request = $this->toRequest();
        try {
            return $this->provider->listBatches($request);
        } catch (RequestException $e) {
            $this->provider->handleRequestException((string) $request->model(), $e);
        }
    }

    public function toRequest(): Request
    {
        return new Request(
            model: $this->model,
            clientOptions: $this->clientOptions,
            clientRetry: $this->clientRetry,
            providerOptions: $this->providerOptions,
            fileName: $this->fileName,
            disk: $this->disk,
            path: $this->path,
            fileOutputId: $this->fileOutputId,
            fileInputId: $this->fileInputId ?? null,
            endPoint: $this->endPoint ?? null,
            completionWindow: $this->completionWindow ?? null,
            batchFileId: $this->batchFileId ?? null,
            provider: $this->provider
        );
    }
}
