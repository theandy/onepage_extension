<?php
defined('TYPO3') or die('Access denied.');

call_user_func(function () {
    $extensionKey = 'onepage_extension';

    // Neuer Doktyp
    $doktype = 201;
    $label   = 'OnePage-Section';
    $iconId  = 'onepage-section';

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

    // Icon
    $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][$doktype] = $iconId;

    // Feldausgabe wie Standard-Seite
    $defaultTypeConst = \TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_DEFAULT;
    if (isset($GLOBALS['TCA']['pages']['types'][$defaultTypeConst])) {
        $GLOBALS['TCA']['pages']['types'][$doktype] = $GLOBALS['TCA']['pages']['types'][$defaultTypeConst];
    }

    // PageTS registrieren
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TsConfig/Page/All.tsconfig',
        'OnePage Extension'
    );
});
