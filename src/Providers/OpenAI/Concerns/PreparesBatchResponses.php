<?php

declare(strict_types=1);

namespace Prism\Prism\Providers\OpenAI\Concerns;

use Illuminate\Support\Arr;
use Prism\Prism\Batch\ListResponse;
use Prism\Prism\Batch\Response;

trait PreparesBatchResponses
{
    /**
     * @param  array<string, mixed>  $responseData
     */
    protected function prepareBatchResponse(array $responseData): Response
    {
        return new Response(
            providerSpecificData: [
                'id' => Arr::get($responseData, 'id'),
                'object' => Arr::get($responseData, 'object'),
                'endpoint' => Arr::get($responseData, 'endpoint'),
                'errors' => Arr::get($responseData['errors'], 'data'),
                'input_file_id' => Arr::get($responseData, 'input_file_id'),
                'completion_window' => Arr::get($responseData, 'completion_window'),
                'status' => Arr::get($responseData, 'status'),
                'output_file_id' => Arr::get($responseData, 'output_file_id'),
                'error_file_id' => Arr::get($responseData, 'error_file_id'),
                'created_at' => Arr::get($responseData, 'created_at'),
                'in_progress_at' => Arr::get($responseData, 'in_progress_at'),
                'expires_at' => Arr::get($responseData, 'expires_at'),
                'finalizing_at' => Arr::get($responseData, 'finalizing_at'),
                'completed_at' => Arr::get($responseData, 'completed_at'),
                'failed_at' => Arr::get($responseData, 'failed_at'),
                'expired_at' => Arr::get($responseData, 'expired_at'),
                'cancelling_at' => Arr::get($responseData, 'cancelling_at'),
                'cancelled_at' => Arr::get($responseData, 'cancelled_at'),
                'request_counts' => Arr::get($responseData, 'request_counts'),
                'metadata' => Arr::get($responseData, 'metadata'),
            ]
        );

    }

    /**
     * @param  array<string, mixed>  $responseData
     */
    protected function prepareBatchListResponse(array $responseData): ListResponse
    {
        return new ListResponse(
            providerSpecificData: [
                'data' => array_map(fn ($batch) => $this->prepareBatchResponse($batch), Arr::get($responseData, 'data', [])),
                'object' => Arr::get($responseData, 'object'),
                'has_more' => Arr::get($responseData, 'has_more'),
                'first_id' => Arr::get($responseData, 'first_id'),
                'last_id' => Arr::get($responseData, 'last_id'),
            ]
        );
    }
}
