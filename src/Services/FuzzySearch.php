<?php

declare(strict_types=1);

namespace Laragear\ArtisanUi\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laragear\ArtisanUi\Services\Command\CommandEntry;
use function strlen;

/**
 * FuzzySearch service for matching commands against user input.
 */
class FuzzySearch
{
    /**
     * Search commands and return matches ranked by relevance to the query.
     *
     * @param  Collection<string, CommandEntry>  $commands  The command data to search through
     * @return Collection<int, CommandEntry>  Ranked matching results
     */
    public function search(Collection $commands, string $query): Collection
    {
        if (! $query) {
            return $commands->sortBy('signature')->values();
        }

        if (Str::endsWith($query, ' ')) {
            return collect();
        }

        // We will put these variables here to avoid making them for each command.
        $query = Str::lower($query);
        $chars = preg_split('//u', $query, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return $commands
            ->map(fn(CommandEntry $command): array => [
                'data' => $command,
                'score' => $this->calculateScore(
                    Str::lower($command->signature),
                    Str::lower($command->description),
                    $chars,
                ),
            ])
            ->filter(static fn(array $result): bool => $result['score'] > 0)
            ->sortByDesc('score')
            ->values()
            ->map(static fn(array $result): CommandEntry => $result['data']);
    }

    /**
     * Calculate the fuzzy match score so relevance ranks commands.
     */
    public function calculateScore(string $signature, string $description, array $chars): int
    {
        $combined = $signature.' '.$description;
        $score = 0;
        $lastIndex = -1;
        $signatureLength = strlen($signature);

        foreach ($chars as $char) {
            $index = strpos($combined, $char, $lastIndex + 1);

            // If the character is not in the combined string, zero points.
            if ($index === false) {
                return 0;
            }

            // Bonus for matching in signature vs. description
            $score += $index < $signatureLength ? 2 : 1;

            // Bonus for consecutive matches
            if ($index === ($lastIndex + 1)) {
                $score += 3;
            }

            // Bonus for matching at word boundaries
            if ($index === 0 || $combined[$index - 1] === ' ') {
                $score += 2;
            }

            $lastIndex = $index;
        }

        return $score;
    }
}
