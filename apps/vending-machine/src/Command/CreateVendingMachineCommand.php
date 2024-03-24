<?php

declare(strict_types=1);

namespace Acme\Ui\Cli\Command;

use Acme\VendingMachine\Application\CreateVendingMachineCommand as DomainCreateVendingMachineCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'machine:create')]
final class CreateVendingMachineCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {
        parent::__construct('machine:create');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandBus->dispatch(new DomainCreateVendingMachineCommand());
        return Command::SUCCESS;
    }
}
