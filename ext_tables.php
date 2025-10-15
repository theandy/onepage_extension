<?php
declare(strict_types=1);

defined('TYPO3') or die('Access denied.');

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;

/**
 * Backend-only registrations
 * - Icon for Doktype 170 (onepage_section)
 *   The Doktype definition itself is done in Configuration/TCA/Overrides/pages.php
 */
(static function (): void {
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(IconRegistry::class);

    // Page type icon
    $iconRegistry->registerIcon(
        'apps-pagetree-onepage-section', // icon identifier used in TCA ctrl[typeicon_classes]
        SvgIconProvider::class,
        ['source' => 'EXT:onepage_extension/Resources/Public/Icons/doktype-onepage-section.svg']
    );
})();
