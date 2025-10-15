<?php
declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

(static function (): void {
    // 1) Plugin registrieren
    ExtensionUtility::registerPlugin(
        'OnepageExtension',                 // Extension-Name (ohne Vendor)
        'OnepageRenderer',                  // Plugin-Name (UpperCamelCase)
        'Onepage Renderer',                 // Label im Backend
        'content-onepage-renderer'          // Icon-Identifier (optional)
    );

    // 2) Plugin-Signatur nach Doku bilden
    $extensionName = 'OnepageExtension';
    $pluginName = 'OnepageRenderer';
    $pluginSignature = strtolower(preg_replace('/[^a-z0-9]/i', '', $extensionName))
        . '_' . strtolower(preg_replace('/[^a-z0-9]/i', '', $pluginName));

    // 3) Icon registrieren
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(IconRegistry::class);
    $iconRegistry->registerIcon(
        'content-onepage-renderer',
        SvgIconProvider::class,
        ['source' => 'EXT:onepage_extension/Resources/Public/Icons/ce-onepage-renderer.svg']
    );

    // 4) Icon für list_type
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['list-' . $pluginSignature]
        = 'content-onepage-renderer';

    // 5) Wizard-Eintrag mit *derselben* Signatur
    ExtensionManagementUtility::addPageTSConfig(<<<TS
mod.wizards.newContentElement.wizardItems.plugins {
  elements {
    onepagerenderer {
      iconIdentifier = content-onepage-renderer
      title = LLL:EXT:onepage_extension/Resources/Private/Language/locallang_db.xlf:plugin.onepage_renderer.title
      description = LLL:EXT:onepage_extension/Resources/Private/Language/locallang_db.xlf:plugin.onepage_renderer.description
      tt_content_defValues {
        CType = list
        list_type = {$pluginSignature}
      }
    }
  }
  show := addToList(onepagerenderer)
}
TS);
})();
