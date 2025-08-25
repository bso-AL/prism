<?php

declare(strict_types=1);

namespace Prism\Prism\Batch;

use Closure;
use Prism\Prism\Concerns\ChecksSelf;
use Prism\Prism\Concerns\HasProviderOptions;

class Request
{
    use ChecksSelf, HasProviderOptions;

    /**
     * @param  array<string, mixed>  $clientOptions
     * @param  array{0: array<int, int>|int, 1?: Closure|int, 2?: ?callable, 3?: bool}  $clientRetry
     * @param  array<string, mixed>  $providerOptions
     */
    public function __construct(
        protected string $model,
        protected ?string $fileName,
        protected ?string $fileOutputId,
        protected array $clientOptions,
        protected array $clientRetry,
        protected ?string $fileInputId,
        protected ?string $endPoint,
        protected ?string $completionWindow,
        protected ?string $batchFileId,
        protected ?string $disk,
        protected ?string $path,
        protected mixed $provider,
        array $providerOptions = []
    ) {
        $this->providerOptions = $providerOptions;
    }

    public function fileInputId(): ?string
    {
        return $this->fileInputId;
    }

    public function endPoint(): ?string
    {
        return $this->endPoint;
    }

    public function completionWindow(): ?string
    {
        return $this->completionWindow;
    }

    public function batchFileId(): ?string
    {
        return $this->batchFileId;
    }

    public function disk(): ?string
    {
        return $this->disk;
    }

    public function path(): ?string
    {
        return $this->path;
    }

    public function model(): ?string
    {
        return $this->model;
    }

    public function provider(): mixed
    {
        return $this->provider;
    }

    /**
     * @return array<string, mixed> $clientOptions
     */
    public function clientOptions(): array
    {
        return $this->clientOptions;
    }

    /**
     * @return array{0: array<int, int>|int, 1?: Closure|int, 2?: ?callable, 3?: bool} $clientRetry
     */
    public function clientRetry(): array
    {
        return $this->clientRetry;
    }
}
