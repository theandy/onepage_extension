<?php
defined('TYPO3') or die('Access denied.');

call_user_func(function () {
    /**
     * Extension key
     */
    $extensionKey = 'onepage_extension';

    /**
     * Neuer Doktyp: OnePage-Section
     */
    $doktype = 201;
    $label   = 'OnePage-Section';
    $iconId  = 'onepage-section'; // Icon in ext_localconf.php registrieren

    // Auswahl im Feld "Seitentyp" ergänzen
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'pages',
        'doktype',
        [
            $label,
            $doktype,
            $iconId,
        ],
        '1',
        'after'
    );

    // Icon-Klasse für diesen Doktyp setzen
    $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][$doktype] = $iconId;

    // Feldkonfiguration für den neuen Typ auf Basis des Standard-Typs übernehmen
    if (isset($GLOBALS['TCA']['pages']['types'][\TYPO3\CMS\Frontend\Page\PageRepository::DOKTYPE_DEFAULT])) {
        $GLOBALS['TCA']['pages']['types'][$doktype] = $GLOBALS['TCA']['pages']['types'][\TYPO3\CMS\Frontend\Page\PageRepository::DOKTYPE_DEFAULT];
    }

    /**
     * PageTS registrieren (aus deiner Originaldatei übernommen)
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TsConfig/Page/All.tsconfig',
        'OnePage Extension'
    );
});
