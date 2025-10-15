<?php

defined('TYPO3') or die('Access denied.');

/**
 * Icon für Doktyp 201: onepage-section
 */
$iconIdentifier = 'onepage-section';
$iconSource     = 'EXT:onepage_extension/Resources/Public/Icons/onepage-section.svg';

/** @var \TYPO3\CMS\Core\Imaging\IconRegistry $registry */
$registry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Imaging\IconRegistry::class
);

try {
    $registry->registerIcon(
        $iconIdentifier,
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => $iconSource]
    );
} catch (\Throwable $e) {
    // Bereits registriert -> ignorieren
}

/**
 * PageTS laden
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:onepage_extension/Configuration/TsConfig/Page/All.tsconfig">'
);

/**
 * TypoScript-Setup und -Constants laden
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'onepage_extension',
    'Configuration/TypoScript/',
    'OnePage Extension'
);
