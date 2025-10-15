<?php
declare(strict_types=1);

namespace AndreasLoewer\OnepageExtension\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\DataProcessing\DataProcessorInterface;

final class OnepageAnchorProcessor implements DataProcessorInterface
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $menuVar   = (string)($processorConfiguration['menuVariable'] ?? 'mainnavigation');
        $doktype   = (int)($processorConfiguration['doktype'] ?? 170);
        $anchorFld = (string)($processorConfiguration['anchorField'] ?? 'slug');

        if (empty($processedData[$menuVar]) || !is_array($processedData[$menuVar])) {
            return $processedData;
        }

        $processedData[$menuVar] = $this->apply($processedData[$menuVar], $doktype, $anchorFld);
        return $processedData;
    }

    private function apply(array $items, int $doktype, string $field): array
    {
        foreach ($items as &$item) {
            $page = $item['data'] ?? [];

            if ((int)($page['doktype'] ?? 0) === $doktype) {
                $anchor = $this->buildAnchor(
                    (string)($page[$field] ?? ''),
                    (string)($page['nav_title'] ?? ''),
                    (string)($page['title'] ?? '')
                );

                // Auf TOP-Ebene wie title, link, target, current etc.
                $item['anchor'] = $anchor;
                $item['sectionLink'] = '#' . $anchor;
                $item['isOnepageSection'] = true;
            }

            if (!empty($item['children']) && is_array($item['children'])) {
                $item['children'] = $this->apply($item['children'], $doktype, $field);
            }
        }
        unset($item);
        return $items;
    }

    private function buildAnchor(string $slug, string $navTitle, string $title): string
    {
        if ($slug !== '') {
            $s = ltrim($slug, '/');
            $s = str_replace('/', '-', $s);
            return $this->normalize($s);
        }
        if ($navTitle !== '') {
            return $this->normalize($navTitle);
        }
        return $this->normalize($title);
    }

    private function normalize(string $text): string
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/u', '-', $text) ?? '';
        return trim($text, '-') ?: 'section';
    }
}
