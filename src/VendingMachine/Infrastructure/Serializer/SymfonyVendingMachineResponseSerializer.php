<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Infrastructure\Serializer;

use Acme\VendingMachine\Application\VendingMachineResponseSerializer;
use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Infrastructure\Normalizer\SymfonyVendingMachineDenormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

final readonly class SymfonyVendingMachineResponseSerializer implements VendingMachineResponseSerializer
{
    /**
     * @throws ExceptionInterface
     */
    #[\Override]
    public function normalize(VendingMachine $vendingMachine): array
    {
        $normalizers = [new SymfonyVendingMachineDenormalizer(), new PropertyNormalizer()];
        $serializer = new Serializer(normalizers: $normalizers, encoders: []);
        return $serializer->normalize($vendingMachine);
    }
}
