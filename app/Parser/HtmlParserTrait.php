<?php
declare(strict_types=1);

namespace App\Parser;

trait HtmlParserTrait
{

    /**
     * @var \DOMDocument
     */
    private $dom;
    private $loaded_url;

    protected function initHtmlParserTrait()
    {
        $this->dom = new \DOMDocument;
        libxml_use_internal_errors(true);
    }

    private function find(array $params): ?array
    {
        extract($params);

        $this->loadHtml($url);

        $list = $this->dom->getElementsByTagName($containing_tag);
        foreach ($list as $item) {
            if (self::hasAttributeValues($item, $attributes) and preg_match_all($pattern, self::getFromParentTree($item, $depth)->textContent, $matches)) {
                return array_map(self::class.'::castToFloat', $matches[1]);
            }
        }
        return null;
    }

    private static function hasAttributeValues(\DOMElement $item, array $data)
    {
        foreach ($data as $attribute => $value) {
            if ($item->getAttribute($attribute) !== $value) {
                return false;
            }
        }

        return true;
    }


    /** @noinspection PhpUnusedPrivateMethodInspection */
    private static function castToFloat(string $str): float
    {
        return (float)str_replace(',', '.', $str);
    }

    private static function getFromParentTree(\DOMNode $item, int $depth): \DOMNode
    {
        for ($i = 0; $i < $depth; $i++) {
            $item = $item->parentNode;
        }

        return $item;

    }

    private function loadHtml(string $url): void
    {
        if ($this->loaded_url !== $url) {
            $this->dom->loadHTMLFile($url);
            $this->loaded_url = $url;
        }
    }


}