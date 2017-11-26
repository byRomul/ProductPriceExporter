<?php

namespace App\Content\ProductPage;

use App\App;
use App\Content\Loader;
use App\Site;
use App\Product;

class Parser
{
    /**
     * @var Site\Site
     */
    private $site;
    /**
     * @var string
     */
    private $loader;

    /**
     * @param Site\Site $site
     * @param Loader $loader
     */
    public function __construct(Site\Site $site, Loader $loader)
    {
        $this->site = $site;
        $this->loader = $loader;
    }

    /**
     * @return Product\Product
     */
    public function parse()
    {
        $product = new Product\Product();
        $product->setSiteId($this->site->getId());
        $product->setUrl($this->loader->getUrl());
        $product->setHash(md5($this->loader->getUrl()));
        $patterns = $this->site->getPatterns();
        $numFoundedPatterns = 0;
        $maxInfoLen = App::instance()->config('maxInfoLen');
        foreach ($patterns as $pattern) {
            $info = $this->parsePattern($pattern->getLeft(), $pattern->getRight());
            $infoLen = mb_strlen($info, $this->site->getCharset());
            if (0 < $infoLen && $infoLen < $maxInfoLen) {
                $numFoundedPatterns++;
                switch ($pattern->getName()) {
                    case 'title':
                        $product->setTitle($info);
                        break;
                    case 'price':
                        $product->setPrice((float)$info);
                        break;
                }
            }
        }
        if ($product->isValid() && $numFoundedPatterns === count($patterns)) {
            return $product;
        } else {
            return null;
        }
    }

    /**
     * @param string $left
     * @param string $right
     * @return string
     */
    public function parsePattern(string $left, string $right)
    {
        $leftPos = mb_strpos($this->loader->getSource(), $left, 0, $this->site->getCharset());
        if ($leftPos === false) {
            return '';
        }
        $leftPos += mb_strlen($left, $this->site->getCharset());
        $rightPos = mb_strpos($this->loader->getSource(), $right, $leftPos, $this->site->getCharset());
        if ($rightPos === false) {
            return '';
        }
        return mb_substr($this->loader->getSource(), $leftPos, $rightPos - $leftPos, $this->site->getCharset());
    }
}