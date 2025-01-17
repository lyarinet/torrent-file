<?php

declare(strict_types=1);

namespace SandFox\Torrent\FileSystem\HybridV1V2;

use SandFox\Torrent\FileSystem\FileData;
use SandFox\Torrent\FileSystem\V1;
use SandFox\Torrent\FileSystem\V2;

class MultipleFileData extends FileData
{
    public function process(): array
    {
        $params = [
            $this->path,
            $this->eventDispatcher,
            $this->pieceLength,
            /* pieceAlign */ 0, // should be 0 for V1 in hybrid mode, ignored on V2
            $this->detectExec,
            $this->detectSymlinks,
        ];

        $v1 = is_dir($this->path) ? new V1\MultipleFileData(...$params) : new V1\SingleFileData(...$params);
        $v2 = new V2\MultipleFileData(...$params);

        $v1data = $v1->process();
        $v2data = $v2->process();

        // $v1data only has info dictionary, merge it
        $v2data['info'] = [...$v1data['info'], ...$v2data['info']];

        return $v2data;
    }
}
