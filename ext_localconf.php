<?php

defined('TYPO3') or die('Access denied.');

/**
 * Icon für Doktyp 201: onepage-section
 */
(call_user_func(function () {
    $iconIdentifier = 'onepage-section';
    $iconSource = 'EXT:onepage_extension/Resources/Public/Icons/onepage-section.svg';

    /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $registry */
    $registry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Imaging\IconRegistry::class
    );

    if (!$registry->isRegistered($iconIdentifier)) {
        $registry->registerIcon(
            $iconIdentifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => $iconSource]
        );
    }
}))();

/**
 * PageTS laden
 * (aus deiner Vorlage übernommen)
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:onepage_extension/Configuration/TsConfig/Page/All.tsconfig">'
);
