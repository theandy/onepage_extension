<?php
declare(strict_types=1);

defined('TYPO3') or die('Access denied.');

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use AndreasLoewer\OnepageExtension\Controller\OnepageController;

// RTE preset
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['onepage_extension']
    = 'EXT:onepage_extension/Configuration/RTE/Default.yaml';

// PageTS
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:onepage_extension/Configuration/TsConfig/Page/All.tsconfig">'
);

// Extbase plugin registration (LIST plugin)
ExtensionUtility::configurePlugin(
    'OnepageExtension',
    'OnepageRenderer',
    [
        OnepageController::class => 'render',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_PLUGIN
);
