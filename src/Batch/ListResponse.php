<?php

declare(strict_types=1);

namespace Prism\Prism\Batch;

class ListResponse
{
    /**
     * @param  array<string, mixed>  $providerSpecificData
     */
    public function __construct(
        public array $providerSpecificData
    ) {}
}
