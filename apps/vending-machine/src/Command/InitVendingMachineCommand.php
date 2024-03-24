<?php

declare(strict_types=1);

namespace Acme\Ui\Cli\Command;

use Acme\Shared\Infrastructure\Symfony\Console\Command\BusCommand;
use Acme\VendingMachine\Application\CreateVendingMachineCommand as DomainCreateVendingMachineCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'machine:init')]
final class InitVendingMachineCommand extends Command
{
    public function __construct(
        private readonly BusCommand $bus
    ) {
        parent::__construct('machine:init');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bus->dispatch(new DomainCreateVendingMachineCommand());
        return Command::SUCCESS;
    }
}
