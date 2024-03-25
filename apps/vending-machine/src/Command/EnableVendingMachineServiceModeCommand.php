<?php

declare(strict_types=1);

namespace Acme\Ui\Cli\Command;

use Acme\Shared\Infrastructure\Symfony\Console\Command\BusCommand;
use Acme\VendingMachine\Application\Service\EnableServiceModeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
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
            '<fg=bright-magenta>--> Enable service mode.</>',
        ]);
        $this->bus->dispatch(command: new EnableServiceModeCommand());
        $io->text([
            '<fg=bright-green>-->--> Service mode enabled.</>',
        ]);
        $printInput = new ArrayInput([
            'command' => 'machine:print',
        ]);
        $this->getApplication()?->doRun($printInput, $output);
        return Command::SUCCESS;
    }
}
