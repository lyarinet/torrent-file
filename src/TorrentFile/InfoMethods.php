<?php

declare(strict_types=1);

namespace SandFox\Torrent\TorrentFile;

use SandFox\Bencode\Encoder;
use SandFox\Bencode\Types\DictType;
use SandFox\Torrent\Exception\InvalidArgumentException;

/**
 * @internal
 */
trait InfoMethods
{
    // info hash cache
    private ?string $infoHash = null;

    public function setPrivate(bool $isPrivate): self
    {
        $this->setInfoField('private', $isPrivate);

        return $this;
    }

    public function isPrivate(): bool
    {
        return \boolval($this->getInfoField('private', false));
    }

    public function isDirectory(): bool
    {
        return $this->getInfoField('files') !== null;
    }

    public function setName(string $name): self
    {
        if ($name === '') {
            throw new InvalidArgumentException('$name must not be empty');
        }
        if (str_contains($name, '/') || str_contains($name, "\0")) {
            throw new InvalidArgumentException('$name must not contain slashes and zero bytes');
        }

        $this->setInfoField('name', $name);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->getInfoField('name');
    }

    public function getInfoHash(): string
    {
        return $this->infoHash ??= sha1(
            (new Encoder())->encode(
                new DictType(
                    $this->getField('info', [])
                )
            )
        );
    }
}
