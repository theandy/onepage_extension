<?php
declare(strict_types=1);

defined('TYPO3') or die('Access denied.');

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Backend registrations
 * - Icon for Doktype 170 (onepage_section)
 * - Static TypoScript template (for Include Sets)
 */
(static function (): void {
    // Icon
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(IconRegistry::class);
    $iconRegistry->registerIcon(
        'apps-pagetree-onepage-section',
        SvgIconProvider::class,
        ['source' => 'EXT:onepage_extension/Resources/Public/Icons/doktype-onepage-section.svg']
    );

    // Static TypoScript (enables "Include TypoScript sets")
    ExtensionManagementUtility::addStaticFile(
        'onepage_extension',
        'Configuration/TypoScript',
        'Onepage Extension'
    );
})();
