<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Infrastructure\Persistence;

use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use Override;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class JsonFileVendingMachineRepository implements VendingMachineRepository
{
    private SerializerInterface $serializer;

    public function __construct(
        private string $persistenceFilePath,
        private Filesystem $filesystem
    ) {
        $normalizers = [new PropertyNormalizer()];
        $encoders = [new JsonEncode()];
        $this->serializer = new Serializer(normalizers: $normalizers, encoders: $encoders);
    }

    #[Override]
    public function save(VendingMachine $vendingMachine): void
    {
        $content = $this->serializer->serialize(
            data: $vendingMachine,
            format: 'json',
        );
        $this->filesystem->dumpFile(filename: $this->persistenceFilePath, content: $content);
    }

    #[Override]
    public function get(): VendingMachine
    {
        return VendingMachine::createDefault();
    }
}
