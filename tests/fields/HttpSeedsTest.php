<?php

declare(strict_types=1);

namespace SandFox\Torrent\Tests\Fields;

use PHPUnit\Framework\TestCase;
use SandFox\Bencode\Bencode;
use SandFox\Torrent\TorrentFile;

class HttpSeedsTest extends TestCase
{
    public function testSetHttpSeeds(): void
    {
        $torrent = TorrentFile::loadFromString('de');

        $torrent->setHttpSeeds([
            'https://example.com/seed',
            'https://example.org/seed',
        ]);

        self::assertEquals([
            'https://example.com/seed',
            'https://example.org/seed',
        ], $torrent->getRawData()['httpseeds']);
    }

    public function testParseHttpSeeds(): void
    {
        $torrent = TorrentFile::loadFromString(Bencode::encode([
            'httpseeds' => [
                'https://example.com/seed',
                'https://example.org/seed',
            ]
        ]));

        self::assertEquals([
            'https://example.com/seed',
            'https://example.org/seed',
        ], $torrent->getHttpSeeds()->toArray());
    }

    public function testSetEmpty(): void
    {
        $torrent = TorrentFile::loadFromString(Bencode::encode([
            'httpseeds' => [
                'https://example.com/seed',
                'https://example.org/seed',
            ]
        ]));

        $torrent->setHttpSeeds(null);

        self::assertArrayNotHasKey('httpseeds', $torrent->getRawData());
    }
}
