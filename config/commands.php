<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Command
    |--------------------------------------------------------------------------
    |
    | Laravel Zero will always run the command specified below when no command name is
    | provided. Consider update the default command for single command applications.
    | You cannot pass arguments to the default command because they are ignored.
    |
    */

    'default' => NunoMaduro\LaravelConsoleSummary\SummaryCommand::class,

    /*
    |--------------------------------------------------------------------------
    | Commands Paths
    |--------------------------------------------------------------------------
    |
    | This value determines the "paths" that should be loaded by the console's
    | kernel. Foreach "path" present on the array provided below the kernel
    | will extract all "Illuminate\Console\Command" based class commands.
    |
    */

    'paths' => [app_path('Commands')],

    /*
    |--------------------------------------------------------------------------
    | Added Commands
    |--------------------------------------------------------------------------
    |
    | You may want to include a single command class without having to load an
    | entire folder. Here you can specify which commands should be added to
    | your list of commands. The console's kernel will try to load them.
    |
    */

    'add' => [
        // ..
        \Illuminate\Console\Scheduling\ScheduleListCommand::class,
        \Illuminate\Console\Scheduling\ScheduleRunCommand::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Hidden Commands
    |--------------------------------------------------------------------------
    |
    | Your application commands will always be visible on the application list
    | of commands. But you can still make them "hidden" specifying an array
    | of commands below. All "hidden" commands can still be run/executed.
    |
    */

    'hidden' => [
        NunoMaduro\LaravelConsoleSummary\SummaryCommand::class,
        Symfony\Component\Console\Command\DumpCompletionCommand::class, // completion
        Symfony\Component\Console\Command\HelpCommand::class, // help
        LaravelZero\Framework\Commands\StubPublishCommand::class, // stub:publish
        \Illuminate\Database\Console\Migrations\MigrateCommand::class, // migrate
        \NunoMaduro\Collision\Adapters\Laravel\Commands\TestCommand::class, // test

        \LaravelZero\Framework\Commands\BuildCommand::class, // app:build
        \LaravelZero\Framework\Commands\InstallCommand::class, // app:install

        \LaravelZero\Framework\Commands\MakeCommand::class, //make:command
        \LaravelZero\Framework\Commands\TestMakeCommand::class, // make:test
        \Illuminate\Database\Console\Migrations\MigrateMakeCommand::class, // make:migration

        \Illuminate\Database\Console\Migrations\FreshCommand::class, // migrate:fresh
        \Illuminate\Database\Console\Migrations\InstallCommand::class, // migrate:install
        \Illuminate\Database\Console\Migrations\RefreshCommand::class, // migrate:refresh
        \Illuminate\Database\Console\Migrations\ResetCommand::class, // migrate:reset
        \Illuminate\Database\Console\Migrations\RollbackCommand::class, // migrate:rollback
        \Illuminate\Database\Console\Migrations\StatusCommand::class, // migrate:status
        \Illuminate\Database\Console\DbCommand::class,
        \Illuminate\Database\Console\WipeCommand::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Removed Commands
    |--------------------------------------------------------------------------
    |
    | Do you have a service provider that loads a list of commands that
    | you don't need? No problem. Laravel Zero allows you to specify
    | below a list of commands that you don't to see in your app.
    |
    */

    'remove' => [
        \Pest\Laravel\Commands\PestInstallCommand::class, // pest:install
        \Pest\Laravel\Commands\PestTestCommand::class, // pest:test
        \Pest\Laravel\Commands\PestDatasetCommand::class, // pest:dataset
        \LaravelZero\Framework\Commands\RenameCommand::class, // app:rename
    ],

];
