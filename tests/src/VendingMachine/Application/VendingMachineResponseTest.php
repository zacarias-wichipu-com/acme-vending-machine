<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application;

use Acme\Store\Domain\Store;
use Acme\VendingMachine\Application\VendingMachineResponse;
use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Infrastructure\Serializer\SymfonyVendingMachineResponseSerializer;
use PHPUnit\Framework\TestCase;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class VendingMachineResponseTest extends TestCase
{
    /**
     * It Should Make A Valid Response
     *
     * @group vending_machine_response
     * @group unit
     */
    public function testItShouldMakeAValidResponse(): void
    {
        $randomVendingMachine = VendingMachineMother::randomMachine();
        $response = new VendingMachineResponse(
            vendingMachine: $randomVendingMachine,
            serializer: new SymfonyVendingMachineResponseSerializer()
        );
        $responseArray = $response->toArray();
        $this->assertVendingMachine(vendingMachine: $randomVendingMachine, responseArray: $responseArray);
        $this->assertStore(store: $randomVendingMachine->store(), storeResponseArray: $responseArray['store']);
        $this->assertWallet(wallet: $randomVendingMachine->wallet(), walletResponseArray: $responseArray['wallet']);
    }

    private function assertVendingMachine(VendingMachine $vendingMachine, array $responseArray): void
    {
        $this->assertArrayHasKey(key: 'status', array: $responseArray);
        $this->assertArrayHasKey(key: 'store', array: $responseArray);
        $this->assertArrayHasKey(key: 'wallet', array: $responseArray);
        $this->assertEquals(
            expected: [
                $vendingMachine->status()->value,
            ],
            actual: [
                $responseArray['status']['value'],
            ]);
    }

    private function assertStore(Store $store, array $storeResponseArray): void
    {
        foreach ($store->racks() as $index => $rack) {
            $this->assertEquals(
                expected: [
                    $rack->price(),
                    $rack->quantity(),
                    $rack->product()->type()->value,
                ],
                actual: [
                    $storeResponseArray['racks'][$index]['price'],
                    $storeResponseArray['racks'][$index]['quantity'],
                    $storeResponseArray['racks'][$index]['product']['type']['value'],
                ]
            );
        }
    }
}
