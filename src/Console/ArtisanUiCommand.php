<?php

declare(strict_types=1);

namespace Laragear\ArtisanUi\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Collection;
use Laragear\ArtisanUi\Services\Command\CommandEntry;
use Laragear\ArtisanUi\Services\CommandDiscoverer;
use Laragear\ArtisanUi\Services\FuzzySearch;
use Laravel\Prompts\Key;
use Laravel\Prompts\SuggestPrompt;
use function Laravel\Prompts\clear;
use function Laravel\Prompts\pause;

/**
 * Interactive Artisan UI command browser.
 */
class ArtisanUiCommand extends Command
{
    /**
     * The name of the console command.
     */
    public const string NAME = 'ui';

    /**
     * The name and signature of the console command.
     */
    protected $signature = self::NAME.'
                            {cmd? : The command to pre-fill in the input}
                            {--H|show-hidden : Suggest hidden commands}
                            {--I|interactive : Return to the command palette once a command completes}';

    /**
     * The console command description.
     */
    protected $description = 'Launch the interactive Artisan UI command browser';

    /**
     * Execute the console command by setting up state and launching the interactive UI.
     */
    public function handle(CommandDiscoverer $discoverer, FuzzySearch $search, Kernel $console): int
    {
        $prefill = (string) $this->argument('cmd');
        $runAgain = false;
        $commands = $this->getCommands($discoverer);

        runCommand:

        clear();

        $command = $this->createPrompt($commands, $search, $prefill, $runAgain)->prompt();

        if ($runAgain) {
            $runAgain = false;
            $prefill = $command.' ';

            goto runCommand;
        }

        $exitCode = $console->call($command, [], $this->output->getOutput());

        if ($this->option('interactive')) {
            pause('Press Enter to return to Artisan UI');

            goto runCommand;
        }

        if ($exitCode === static::FAILURE) {
            return static::FAILURE;
        }

        return static::SUCCESS;
    }

    /**
     * Creates a Suggestion Prompt.
     *
     * @param  Collection<string, CommandEntry>  $commands
     */
    protected function createPrompt(
        Collection $commands,
        FuzzySearch $search,
        string $prefill,
        bool &$runAgain,
    ): SuggestPrompt {

        $prompt = new SuggestPrompt(
            label: '⚡ Laragear Artisan UI',
            options: static fn(string $input): Collection => $search
                ->search($commands, $input)
                ->unless($input)
                ->sortBy('signature')
                ->map(static fn(CommandEntry $command): string => $command->signature),
            placeholder: 'Type a command or pick one suggestion',
            default: $prefill,
            scroll: $commands->count(),
            required: true,
            info: static fn(?string $highlighted): ?string => $commands->get($highlighted)?->description,
        );

        $prompt->on('key', static function (string $key) use ($prompt, &$runAgain): void {
            if ($prompt->highlightedValue() && $key === Key::ENTER) {
                $runAgain = true;
            }
        });

        return $prompt;
    }

    /**
     * Return all the discovered commands.
     *
     * @return Collection<string, CommandEntry>
     */
    protected function getCommands(CommandDiscoverer $discoverer): Collection
    {
        return $discoverer->showHidden((bool) $this->option('show-hidden'))->commands();
    }
}
