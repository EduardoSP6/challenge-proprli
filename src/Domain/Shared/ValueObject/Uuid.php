<?php

namespace Domain\Shared\ValueObject;

use Illuminate\Support\Str;
use InvalidArgumentException;

final class Uuid
{
    private readonly string $uuid;

    public function __construct(?string $uuid = null)
    {
        $this->uuid = $uuid ?? Str::orderedUuid()->toString();
        $this->validate();
    }

    protected function validate(): void
    {
        if (!preg_match(
            '/^\{?([0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12})$/i',
            $this->uuid)
        ) {
            throw new InvalidArgumentException("UUID inválido");
        }
    }

    public function value(): string
    {
        return $this->uuid;
    }
}

