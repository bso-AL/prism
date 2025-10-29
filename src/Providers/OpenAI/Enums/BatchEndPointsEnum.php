<?php

namespace Prism\Prism\Providers\OpenAI\Enums;

enum BatchEndPointsEnum: string
{
    case ChatCompletions = '/v1/chat/completions';
    case Completions = '/v1/completions';
    case Responses = '/v1/responses';
    case Embeddings = '/v1/embeddings';
}
