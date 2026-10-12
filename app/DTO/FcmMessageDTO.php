<?php

declare(strict_types=1);

namespace App\DTO;

use Illuminate\Contracts\Support\Arrayable;

class FcmMessageDTO implements Arrayable
{
    public function __construct(
        public string $title,
        public string $message
    )
    {
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message
        ];
    }
}
