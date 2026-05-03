<?php

namespace Laragear\ArtisanUi\Services\Command;

readonly class CommandOption
{
    /**
     * Create a new Command Option instance.
     */
    public function __construct(
        public string $name,
        public string $shortcut,
        public string $description,
        public string|bool|int|float|array|null $default,
    ) {
        //
    }
}
