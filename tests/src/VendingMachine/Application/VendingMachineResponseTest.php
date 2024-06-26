<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application;

use Acme\Store\Domain\Store;
use Acme\VendingMachine\Application\VendingMachineResponse;
use Acme\VendingMachine\Domain\VendingMachine;
use Acme\Wallet\Domain\Coins;
use Generator;
use PHPUnit\Framework\TestCase;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class VendingMachineResponseTest extends TestCase
{
    /**
     * It Should Make A Valid Response
     *
     * @dataProvider vendingMachineData
     * @group vending_machine_response
     * @group unit
     */
    public function testItShouldMakeAValidResponse(VendingMachine $vendingMachine): void
    {
        $response = new VendingMachineResponse(vendingMachine: $vendingMachine);
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

    public static function vendingMachineData(): Generator
    {
        yield [VendingMachineMother::defaultMachine()];
        yield [VendingMachineMother::randomMachine()];
        yield [VendingMachineMother::randomMachine()];
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
                $responseArray['status'],
            ]
        );
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
                    $storeResponseArray[$index]['price'],
                    $storeResponseArray[$index]['quantity'],
                    $storeResponseArray[$index]['product'],
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
                    $exchangeCoinsResponseArray[$index]['coin'],
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
