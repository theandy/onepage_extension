<?php


defined('TYPO3') or die('Access denied.');


// PageTS laden
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:onepage_extension/Configuration/TsConfig/Page/All.tsconfig">'
);


// TypoScript (Setup/Constants) laden
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'onepage_extension',
    'Configuration/TypoScript/',
    'OnePage Extension'
);
