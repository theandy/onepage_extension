<?php
declare(strict_types=1);

namespace AndreasLoewer\OnepageExtension\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

final class OnepageController extends ActionController
{
    public function renderAction(): ResponseInterface
    {
        $settings = $this->settings ?? [];
        $anchorFrom = $settings['anchorFrom'] ?? 'slug';

        $pageId = (int)($GLOBALS['TSFE']->id ?? 0);
        $sections = $this->fetchSections($pageId, 170, $anchorFrom);

        $this->view->assignMultiple([
            'sections' => $sections,
            'settings' => ['anchorFrom' => $anchorFrom],
        ]);

        return $this->htmlResponse();
    }

    /**
     * @return array<int, array{uid:int,title:string,nav_title:?string,slug:?string,anchor:string,contentUids:int[]}>
     */
    private function fetchSections(int $pid, int $doktype, string $anchorFrom): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $rows = $qb
            ->select('uid', 'title', 'nav_title', 'slug')
            ->from('pages')
            ->where(
                $qb->expr()->eq('pid', $qb->createNamedParameter($pid, \PDO::PARAM_INT)),
                $qb->expr()->eq('doktype', $qb->createNamedParameter($doktype, \PDO::PARAM_INT))
            )
            ->orderBy('sorting', 'ASC')
            ->executeQuery()
            ->fetchAllAssociative();

        $sections = [];
        foreach ($rows as $row) {
            $uid = (int)$row['uid'];
            $sections[] = [
                'uid' => $uid,
                'title' => (string)$row['title'],
                'nav_title' => $row['nav_title'] !== null ? (string)$row['nav_title'] : null,
                'slug' => $row['slug'] !== null ? (string)$row['slug'] : null,
                'anchor' => $this->buildAnchor($row, $anchorFrom),
                // Alle Inhalte der Seite, ohne colPos-Filter
                'contentUids' => $this->fetchContentUids($uid),
            ];
        }
        return $sections;
    }

    /**
     * @param array{title?:string,nav_title?:?string,slug?:?string} $pageRow
     */
    private function buildAnchor(array $pageRow, string $anchorFrom): string
    {
        if ($anchorFrom === 'slug' && !empty($pageRow['slug'])) {
            $slug = ltrim((string)$pageRow['slug'], '/');
            $slug = str_replace('/', '-', $slug);
            return $slug !== '' ? $slug : $this->normalize(($pageRow['nav_title'] ?? $pageRow['title'] ?? 'section'));
        }
        if ($anchorFrom === 'nav_title' && !empty($pageRow['nav_title'])) {
            return $this->normalize((string)$pageRow['nav_title']);
        }
        return $this->normalize((string)($pageRow['title'] ?? 'section'));
    }

    private function normalize(string $text): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/u', '-', $text) ?? '';
        $text = trim($text, '-');
        return $text !== '' ? $text : 'section';
    }

    /**
     * @return int[]
     */
    private function fetchContentUids(int $pid): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $uids = $qb
            ->select('uid')
            ->from('tt_content')
            ->where(
                $qb->expr()->eq('pid', $qb->createNamedParameter($pid, \PDO::PARAM_INT))
            )
            ->orderBy('sorting', 'ASC')
            ->executeQuery()
            ->fetchFirstColumn();

        return array_map('intval', $uids ?: []);
    }
}
