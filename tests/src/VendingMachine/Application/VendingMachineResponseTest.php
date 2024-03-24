<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application;

use Acme\Store\Domain\Store;
use Acme\VendingMachine\Application\VendingMachineResponse;
use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Infrastructure\Serializer\SymfonyVendingMachineResponseSerializer;
use Acme\Wallet\Domain\Coins;
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
        $vendingMachine = VendingMachineMother::defaultMachine();
        $response = new VendingMachineResponse(
            vendingMachine: $vendingMachine,
            serializer: new SymfonyVendingMachineResponseSerializer()
        );
        $responseArray = $response->toArray();
        $this->assertVendingMachine(vendingMachine: $vendingMachine, responseArray: $responseArray);
        $this->assertStore(store: $vendingMachine->store(), storeResponseArray: $responseArray['store']);
        $this->assertExchangeWallet(
            exchangeCoins: $vendingMachine->wallet()->exchangeCoins(),
            exchangeCoinsResponseArray: $responseArray['wallet']['exchangeCoins']
        );
        $this->assertCustomerWallet(
            customerCoins: $vendingMachine->wallet()->customerCoins(),
            customerCoinsResponseArray: $responseArray['wallet']['customerCoins']
        );
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

    private function assertExchangeWallet(Coins $exchangeCoins, array $exchangeCoinsResponseArray): void
    {
        foreach ($exchangeCoins as $index => $coinBox) {
            $this->assertEquals(
                expected: [
                    $coinBox->coin()->amountInCents()->value,
                    $coinBox->quantity(),
                ],
                actual: [
                    $exchangeCoinsResponseArray[$index]['coin']['amountInCents']['value'],
                    $exchangeCoinsResponseArray[$index]['quantity'],
                ]
            );
        }
    }
    private function assertCustomerWallet(Coins $customerCoins, array $customerCoinsResponseArray): void
    {
        foreach ($customerCoins as $index => $coinBox) {
            $this->assertEquals(
                expected: [
                    $coinBox->coin()->amountInCents()->value,
                    $coinBox->quantity(),
                ],
                actual: [
                    $customerCoinsResponseArray[$index]['coin']['amountInCents']['value'],
                    $customerCoinsResponseArray[$index]['quantity'],
                ]
            );
        }
    }
}
