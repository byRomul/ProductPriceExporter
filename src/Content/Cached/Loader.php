<?php

namespace App\Content\Cached;

use App\App;

class Loader extends \App\Content\Loader
{
    /**
     * @inheritdoc
     */
    public function getSource()
    {
        if ($this->isCached()) {
            return $this->getCache();
        }
        $result = parent::getSource();
        if ($result !== false) {
            file_put_contents($this->getCacheFilePath(), $result);
            return $result;
        }
        return false;
    }

    /**
     * @return bool
     */
    private function isCached(): bool
    {
        return file_exists($this->getCacheFilePath());
    }

    /**
     * @return bool|string
     */
    private function getCache()
    {
        return file_get_contents($this->getCacheFilePath());
    }

    /**
     * @return string
     */
    private function getCacheFilePath(): string
    {
        $hash = md5($this->url);
        $dirs = [0 => App::instance()->config('pathToCache')];
        $dirs[1] = $dirs[0] . '/' . App::instance()->now()->format('Ymd');
        $dirs[2] = $dirs[1] . '/' . substr($hash, 0, 2);
        if (!file_exists($dirs[2])) {
            foreach ($dirs as $dir) {
                if (!file_exists($dir)) {
                    mkdir($dir);
                }
            }
        }
        return $dirs[2] . '/' . $hash;
    }
}