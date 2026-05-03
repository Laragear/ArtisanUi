<?php

declare(strict_types=1);

namespace Laragear\ArtisanUi\Services;

use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Support\Collection;
use Laragear\ArtisanUi\Console\ArtisanUiCommand;
use Laragear\ArtisanUi\Services\Command\CommandArgument;
use Laragear\ArtisanUi\Services\Command\CommandEntry;
use Laragear\ArtisanUi\Services\Command\CommandOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use function collect;

/**
 * Service that discovers Artisan commands from the application kernel with caching.
 */
class CommandDiscoverer
{
    /**
     * The in-memory collection of commands.
     *
     * @var Collection<string, CommandEntry>
     */
    protected Collection $commands;

    /**
     * Should have hidden commands be shown.
     */
    protected bool $showHidden = false;

    /**
     * Create a new Command Discovery instance.
     */
    public function __construct(
        protected readonly ConsoleKernelContract $kernel,
    ) {
        //
    }

    /**
     * Shows or hide the commands.
     *
     * @return $this
     */
    public function showHidden(bool $hidden = true): static
    {
        $this->showHidden = $hidden;

        return $this;
    }

    /**
     * Discover all Artisan commands from the kernel.
     *
     * @return Collection<string, CommandEntry>
     */
    public function commands(): Collection
    {
        return $this->commands ??= $this->discoverFromApplication();
    }

    /**
     * Discover commands directly from the Laravel application instance.
     *
     * @return Collection<string, CommandEntry>
     */
    protected function discoverFromApplication(): Collection
    {
        return collect($this->kernel->all())
            ->forget([ArtisanUiCommand::NAME]) // Don't show the UI command.
            ->unless($this->showHidden)
            ->reject(static fn(Command $command): bool => $command->isHidden())
            ->mapWithKeys(fn(Command $command): array => [
                $command->getName() => new CommandEntry(
                    $command->getName(),
                    $command->getDescription(),
                    $this->extractArguments($command),
                    $this->extractOptions($command),
                ),
            ]);
    }

    /**
     * Extract arguments so the UI can display what inputs a command expects.
     *
     * @return Collection<string, CommandArgument>
     */
    protected function extractArguments(Command $command): Collection
    {
        return collect($command->getDefinition()->getArguments())
            ->mapWithKeys(static fn(InputArgument $argument): array => [
                $argument->getName() => new CommandArgument(
                    $argument->getName(),
                    $argument->getDescription(),
                    ! $argument->isRequired(),
                    $argument->getDefault(),
                ),
            ]);
    }

    /**
     * Extract options so the UI can display what flags are available.
     *
     * @return Collection<string, CommandOption>
     */
    protected function extractOptions(Command $command): Collection
    {
        return collect($command->getDefinition()->getOptions())
            ->mapWithKeys(static fn(InputOption $option): array => [
                $option->getName() => new CommandOption(
                    $option->getName(),
                    (string) $option->getShortcut(),
                    $option->getDescription(),
                    $option->getDefault(),
                ),
            ]);
    }
}
