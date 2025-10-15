<?php
defined('TYPO3') or die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'onepage_extension',
    'Configuration/TypoScript/',
    'OnePage Extension'
);
