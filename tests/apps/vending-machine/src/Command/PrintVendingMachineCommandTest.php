<?php

declare(strict_types=1);

namespace Tests\Acme\Ui\Cli\Command;

use Acme\Ui\Cli\Command\PrintVendingMachineCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Acme\Shared\Infrastructure\PhpUnit\AppContextInfrastructureTestCase;

class PrintVendingMachineCommandTest extends AppContextInfrastructureTestCase
{
    /**
     * It Should Return A Success Response
     *
     * @group print_vending_machine_command
     * @group application
     */
    public function testItShouldReturnASuccessResponse(): void
    {
        $command =  new PrintVendingMachineCommand($this->service('console_command.messenger_bus'));
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $commandTester->assertCommandIsSuccessful();
    }
}
