<?php

namespace Laragear\ArtisanUi\Services\Command;

class CommandArgument
{
    /**
     * Create a new Command Option instance.
     */
    public function __construct(
        public string $name,
        public string $description,
        public bool $optional,
        public string|bool|int|float|array|null $default,
    ) {
        //
    }
}
