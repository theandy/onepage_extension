<?php
declare(strict_types=1);

defined('TYPO3') or die('Access denied.');

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * TCA override: register Doktype 170 "Onepage-Abschnitt"
 * and PageTSConfig include.
 */
(function (): void {
    $extensionKey = 'onepage_extension';
    $doktype = 170; // onepage_section

    // 1) PageTS einbinden (wie bisher)
    ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TsConfig/Page/All.tsconfig',
        'OnePage Extension'
    );

    // 2) Neuen Doktype in TCA::pages registrieren
    // 2a) Label + Icon im Doktype-Dropdown
    $GLOBALS['TCA']['pages']['columns']['doktype']['config']['items'][] = [
        // Label
        'LLL:EXT:onepage_extension/Resources/Private/Language/locallang_db.xlf:pages.doktype.onepage_section',
        // Wert
        $doktype,
        // Icon Identifier (aus ext_tables.php registriert)
        'apps-pagetree-onepage-section',
    ];

    // 2b) Icon-Zuordnung für Pagetree
    $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][$doktype] = 'apps-pagetree-onepage-section';

    // 2c) Optional: Typ-Konfiguration übernehmen (Anzeige wie Standard-Seite)
    // Wenn keine spezielle "types" Konfiguration nötig ist, nutzt TYPO3 die Fallback-Definition.
    // Hier dennoch ein expliziter Eintrag für Klarheit:
    $GLOBALS['TCA']['pages']['types'][$doktype] = $GLOBALS['TCA']['pages']['types'][\TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_DEFAULT] ?? [];

    // 2d) Optional: Felder für diesen Doktype ausblenden (Navigation, Cache, etc.)
    // Das Feintuning erfolgt typischerweise via PageTSConfig. Beispiel:
    // $GLOBALS['TCA']['pages']['types'][$doktype]['showitem'] = $GLOBALS['TCA']['pages']['types'][1]['showitem'];
})();
