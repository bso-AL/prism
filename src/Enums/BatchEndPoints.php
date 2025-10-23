<?php

declare(strict_types=1);

namespace Prism\Prism\Enums;

enum BatchEndPoints: string
{
    case OPENAI_EMBEDDINGS = '/v1/embeddings';
    case OPENAI_CHAT_COMPLETIONS = '/v1/chat/completions';
    case OPENAI_COMPLETIONS = '/v1/completions';
    case OPENAI_RESPONSES = '/v1/responses';
}
