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
        'OnepageExtension',
        'OnepageRenderer',
        'Onepage Renderer',
        'content-onepage-renderer'
    );

    // 2) Plugin-Signatur gemäß Doku
    $extensionName = 'OnepageExtension';
    $pluginName = 'OnepageRenderer';
    $pluginSignature = strtolower(preg_replace('/[^a-z0-9]/i', '', $extensionName))
        . '_' . strtolower(preg_replace('/[^a-z0-9]/i', '', $pluginName));

    // 3) Icon
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(IconRegistry::class);
    $iconRegistry->registerIcon(
        'content-onepage-renderer',
        SvgIconProvider::class,
        ['source' => 'EXT:onepage_extension/Resources/Public/Icons/ce-onepage-renderer.svg']
    );
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['list-' . $pluginSignature]
        = 'content-onepage-renderer';

    // 4) EXPLIZITEN Select-Eintrag für list_type hinzufügen
    $GLOBALS['TCA']['tt_content']['columns']['list_type']['config']['items'][] = [
        'Onepage Renderer',
        $pluginSignature,
        'content-onepage-renderer',
    ];

    // 5) Wizard-Eintrag
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
TS
    );

    // 6) Optional: Subtypes-Handling (FlexForm etc.) – derzeit nichts nötig
    // $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = '';
})();
