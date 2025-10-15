<?php
defined('TYPO3') or die('Access denied.');

call_user_func(function () {
    $doktype = 201;
    $label   = 'OnePage-Section';
    $iconId  = 'onepage-section';

    // Eintrag im "Seitentyp"-Feld direkt setzen, ohne addTcaSelectItem()
    $GLOBALS['TCA']['pages']['columns']['doktype']['config']['items'][] = [
        $label,
        $doktype,
        $iconId,
    ];

    // Icon registrieren
    $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][$doktype] = $iconId;

    // Feldausgabe wie Standard-Seite übernehmen
    $defaultType = \TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_DEFAULT;
    if (!empty($GLOBALS['TCA']['pages']['types'][$defaultType])) {
        $GLOBALS['TCA']['pages']['types'][$doktype] = $GLOBALS['TCA']['pages']['types'][$defaultType];
    }
});
