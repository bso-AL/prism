<?php

declare(strict_types=1);

namespace Prism\Prism\Concerns;

use Prism\Prism\Batch\ListResponse as BatchListResponse;
use Prism\Prism\Batch\Request as BatchRequest;
use Prism\Prism\Batch\Response as BatchResponse;
use Prism\Prism\Exceptions\PrismException;

trait HasBatchFunctionality
{
    public function processBatch(BatchRequest $request): BatchResponse
    {
        throw PrismException::unsupportedProviderAction('processBatch', class_basename($this));
    }

    public function retrieveBatch(BatchRequest $request): BatchResponse
    {
        throw PrismException::unsupportedProviderAction('retrieveBatch', class_basename($this));
    }

    public function listBatches(BatchRequest $request): BatchListResponse
    {
        throw PrismException::unsupportedProviderAction('listBatches', class_basename($this));
    }
}
