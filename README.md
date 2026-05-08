# Artisan UI

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laragear/artisan-ui.svg)](https://packagist.org/packages/laragear/artisan-ui)
[![Latest stable test run](https://github.com/Laragear/ArtisanUi/workflows/Tests/badge.svg)](https://github.com/Laragear/ArtisanUi/actions)
[![Codecov coverage](https://codecov.io/gh/Laragear/ArtisanUi/graph/badge.svg?token=maINUek7ul)](https://codecov.io/gh/Laragear/ArtisanUi)
[![Maintainability](https://qlty.sh/gh/Laragear/projects/ArtisanUi/maintainability.svg)](https://qlty.sh/gh/Laragear/projects/ArtisanUi)
[![Sonarcloud Status](https://sonarcloud.io/api/project_badges/measure?project=Laragear_ArtisanUi&metric=alert_status)](https://sonarcloud.io/dashboard?id=Laragear_ArtisanUi)

Interactive Artisan command browser UI.

    > php artisan ui

    ┌ ⚡ Laragear Artisan UI ──────────────────────────────────────┐
    │ ab                                                           │
    ├──────────────────────────────────────────────────────────────┤
    │ › about                                                    ┃ │
    │   auth:clear-resets                                        │ │
    │   serve                                                    │ │
    │   vendor:publish                                           │ │
    │   schedule:list                                            │ │
    └──────────── Display basic information about your application ┘


## Keep this package free

[![](.github/assets/support.png)](https://github.com/sponsors/DarkGhostHunter)

Your support allows me to keep this package free, up-to-date and maintainable. Alternatively, you can **spread the word on social media**.

## Requirements

* PHP 8.3 or later
* Laravel 12 or later

## How does this work?

Artisan UI is an interactive command browser for Laravel. It adds a rich, searchable terminal interface with fuzzy search using Laravel Prompts.

## Installation

You can install the package via composer:

```bash
composer require laragear/artisan-ui
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider=Laragear\ArtisanUi\ArtisanUiServiceProvider --tag="config"
```

## Usage

Simply run `php artisan` without any arguments to launch the interactive UI.

Alternatively, you can call the `ui` command explicitly:

```bash
php artisan ui
```

### Show hidden commands

By default, commands that are set to be hidden are not shown in the suggestion list. If you wish to show these commands, use the `--show-hidden` flag.

```shell
php artisan ui --show-hidden
```

> [!NOTE]
> 
> Hidden commands are still executable. The command input does not pose restrictions on what command to run.

## Zed Editor Task

If you're using Zed, you will notice that tasks do not accept arguments. Laragear Artisan UI fixes this problem by showing a command browser. Simply add this task to your editor and you're all set.

```json
{
    "label": "laragear artisan ui",
    "command": "php",
    "reveal": "always",
    "save": "all",
    "args": [
      "$ZED_WORKTREE_ROOT/artisan",
      "ui"
    ]
}
```

If you're using a remote container for PHP like [Laragear/PHP](https://github.com/Laragear/php), you can use _Docker_ (or similar container runtime like [Podman](https://podman.io/) or [Rancher](https://www.rancher.com/)):

```json
{
    "label": "laravel artisan",
    "command": "docker",
    "reveal": "always",
    "save": "all",
    "args": [
        "run",
        "--rm",
        "-v",
        "$ZED_WORKTREE_ROOT:/app",
        "-u",
        "1000:1000",
        "-w",
        "/app",
        "-it",
        "laragear/php",
        "/usr/local/bin/php",
        "/app/artisan"
    ]
}
```

> [!TIP]
> 
> For more information, check out the [Zed Editor tasks documentation](https://zed.dev/docs/tasks).

## Security

If you discover any security-related issues, please issue a security advisory.

# License

This specific package version is licensed under the terms of the [MIT License](LICENSE.md), at the time of publishing.

[Laravel](https://laravel.com) is a Trademark of [Taylor Otwell](https://github.com/TaylorOtwell/). Copyright © 2011–2026 Laravel LLC.
