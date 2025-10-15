<?php
defined('TYPO3') or die('Access denied.');


call_user_func(function () {
    $doktype = 201;
    $label = 'OnePage-Section';
    $iconId = 'onepage-section';


// Auswahl im Feld "Seitentyp" ergänzen (direkt, ohne EMU)
    $GLOBALS['TCA']['pages']['columns']['doktype']['config']['items'][] = [
        $label,
        $doktype,
        $iconId,
    ];


// Icon-Klasse
    $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][$doktype] = $iconId;


// Feldausgabe wie Standard-Seite
    $defaultType = \TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_DEFAULT;
    if (!empty($GLOBALS['TCA']['pages']['types'][$defaultType])) {
        $GLOBALS['TCA']['pages']['types'][$doktype] = $GLOBALS['TCA']['pages']['types'][$defaultType];
    }
});
