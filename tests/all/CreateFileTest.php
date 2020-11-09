<?php

declare(strict_types=1);

namespace SandFox\Torrent\Tests\All;

use PHPUnit\Framework\TestCase;
use SandFox\Torrent\TorrentFile;

use const SandFox\Torrent\Tests\TEST_ROOT;

class CreateFileTest extends TestCase
{
    public function testSingleFile(): void
    {
        $torrent = TorrentFile::fromPath(TEST_ROOT . '/data/files/file1.txt'); // approx 6 mb

        $this->assertEquals('6ca8fecb8d4c43183307179652fd31a50f99a912', $torrent->getInfoHash());
        $this->assertEquals(260, strlen($torrent->getRawData()['info']['pieces'])); // 13 chunks
        $this->assertEquals('file1.txt', $torrent->getDisplayName());
        $this->assertEquals('file1.txt.torrent', $torrent->getFileName());

        $this->assertEquals(
            'magnet:?dn=file1.txt&xt=urn:btih:6CA8FECB8D4C43183307179652FD31A50F99A912',
            $torrent->getMagnetLink()
        );
    }

    public function testMultipleFiles(): void
    {
        $torrent = TorrentFile::fromPath(TEST_ROOT . '/data/files'); // approx 19 mb

        $this->assertEquals('2efb80c60b42b261d79b777477276e0b18b47081', $torrent->getInfoHash());
        $this->assertEquals(760, strlen($torrent->getRawData()['info']['pieces'])); // 38 chunks
        $this->assertEquals('files', $torrent->getDisplayName());
        $this->assertEquals('files.torrent', $torrent->getFileName());

        $this->assertEquals(
            'magnet:?dn=files&xt=urn:btih:2EFB80C60B42B261D79B777477276E0B18B47081',
            $torrent->getMagnetLink()
        );
    }

    public function testMultipleFiles1MB(): void
    {
        $torrent = TorrentFile::fromPath(TEST_ROOT . '/data/files', [
            'pieceLength' => 1024 * 1024, // 1mb chunk
        ]); // approx 19 mb

        $this->assertEquals('5af63cdfb9cdcc9a09bde3fa4b7a9266d8528b7a', $torrent->getInfoHash());
        $this->assertEquals(380, strlen($torrent->getRawData()['info']['pieces'])); // 19 chunks
        $this->assertEquals('files', $torrent->getDisplayName());
        $this->assertEquals('files.torrent', $torrent->getFileName());

        $this->assertEquals(
            'magnet:?dn=files&xt=urn:btih:5AF63CDFB9CDCC9A09BDE3FA4B7A9266D8528B7A',
            $torrent->getMagnetLink()
        );
    }
}
