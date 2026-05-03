<?php

namespace Laragear\ArtisanUi\Services\Command;

use Illuminate\Support\Collection;

readonly class CommandEntry
{
    /**
     * Create a new Command Entry instance.
     *
     * @param \Illuminate\Support\Collection<string, \Laragear\ArtisanUi\Services\Command\CommandArgument>  $arguments
     * @param \Illuminate\Support\Collection<string, \Laragear\ArtisanUi\Services\Command\CommandOption>  $options
     */
    public function __construct(
        public string $signature,
        public string $description,
        public Collection $arguments,
        public Collection $options,
    ) {
        //
    }
}
