<?php

declare(strict_types=1);

namespace SandFox\Torrent\TorrentFile\Fields;

use SandFox\Torrent\DataTypes\UriList;

trait UrlList
{
    private ?UriList $urlList = null;

    public function getUrlList(): UriList
    {
        $urlList = $this->data['url-list'] ?? [];
        // additional handling in case url-list is a string not array of strings
        return $this->urlList ??= new UriList(\is_array($urlList) ? $urlList : [$urlList]);
    }

    /**
     * @param UriList|iterable<string>|null $value
     */
    public function setUrlList($value): self
    {
        // always store as list
        $this->data['url-list'] = $this->urlList = $value instanceof UriList ? $value : new UriList($value ?? []);
        return $this;
    }
}
