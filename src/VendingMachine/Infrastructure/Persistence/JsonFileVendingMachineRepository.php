<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Infrastructure\Persistence;

use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use Acme\VendingMachine\Domain\VendorRepositoryException;
use Acme\VendingMachine\Infrastructure\Normalizer\SymfonyVendingMachineDenormalizer;
use JsonException;
use Override;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\JsonDecode;
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
        $this->serializer = $this->buildSerializer();
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

    /**
     * @throws JsonException
     */
    #[Override]
    public function get(): VendingMachine
    {
        $this->ensureJsonFilePersistenceExists();
        $content = file_get_contents($this->persistenceFilePath);
        return $this->serializer->deserialize(
            data: $content,
            type: VendingMachine::class,
            format: 'json'
        );
    }

    private function buildSerializer(): SerializerInterface
    {
        $normalizers = [new SymfonyVendingMachineDenormalizer(), new PropertyNormalizer()];
        $encoders = [new JsonEncode(), new JsonDecode()];
        return new Serializer(normalizers: $normalizers, encoders: $encoders);
    }

    private function ensureJsonFilePersistenceExists(): void
    {
        if (! $this->filesystem->exists(files: $this->persistenceFilePath)) {
            throw new VendorRepositoryException(
                message: sprintf('The JSON file <%1$s> doesn\'t exists.', $this->persistenceFilePath)
            );
        }
    }
}
