<?php

declare(strict_types=1);

namespace Prism\Prism\Providers\OpenAI\Batch;

class Response
{
    /**
     * @param  array<string, mixed>  $providerSpecificData
     */
    public function __construct(
        public readonly array $providerSpecificData,

    ) {}

}
