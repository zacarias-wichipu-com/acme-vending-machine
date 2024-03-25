<?php

declare(strict_types=1);

namespace Acme\Ui\Cli\Command;

use Acme\Shared\Infrastructure\Symfony\Console\Command\BusCommand;
use Acme\VendingMachine\Application\Service\EnableServiceModeCommand;
use Acme\VendingMachine\Domain\Exception\ServiceModeUnavailable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'service:enable')]
final class EnableVendingMachineServiceModeCommand extends Command
{
    public function __construct(
        private readonly BusCommand $bus
    ) {
        parent::__construct('service:enable');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->text([
            '<fg=bright-magenta>--> Enabling service mode.</>',
        ]);
        try {
            $this->bus->dispatch(command: new EnableServiceModeCommand());
        } catch (ServiceModeUnavailable $exception) {
            $io->text([
                sprintf('<fg=bright-red>-->--> %1$s</>', $exception->getMessage()),
            ]);
            return Command::SUCCESS;
        }
        $io->text([
            '<fg=bright-green>-->--> Service mode enabled.</>',
        ]);
        return Command::SUCCESS;
    }
}
