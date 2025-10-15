<?php
declare(strict_types=1);

defined('TYPO3') or die('Access denied.');

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use AndreasLoewer\OnepageExtension\Controller\OnepageController;

/**
 * RTE preset
 */
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['onepage_extension']
    = 'EXT:onepage_extension/Configuration/RTE/Default.yaml';

/**
 * PageTS
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:onepage_extension/Configuration/TsConfig/Page/All.tsconfig">'
);

/**
 * Extbase plugin registration: OnepageRenderer
 * - Controller: OnepageController::render
 * - Plugin type: content element
 */
ExtensionUtility::configurePlugin(
    'OnepageExtension',               // Extension name (Vendor is inferred from composer)
    'OnepageRenderer',                // Plugin key
    [
        OnepageController::class => 'render',
    ],
    [
        // no non-cacheable actions
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
