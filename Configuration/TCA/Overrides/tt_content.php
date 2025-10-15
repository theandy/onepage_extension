<?php
declare(strict_types=1);

defined('TYPO3') or die('Access denied.');

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

(static function (): void {
    // 1) Plugin registrieren und *tatsächliche* Signatur erhalten
    $pluginSignature = ExtensionUtility::registerPlugin(
        'OnepageExtension',                // Extension-Name (UpperCamelCase, ohne Vendor)
        'OnepageRenderer',                 // Plugin-Name (UpperCamelCase)
        'Onepage Renderer',                // Label im Backend
        'content-onepage-renderer'         // Icon-Identifier (optional)
    );

    // 2) Icon registrieren (falls noch nicht global vorhanden)
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(IconRegistry::class);
    $iconRegistry->registerIcon(
        'content-onepage-renderer',
        SvgIconProvider::class,
        ['source' => 'EXT:onepage_extension/Resources/Public/Icons/ce-onepage-renderer.svg']
    );

    // 3) TCA: Icon für diesen list_type zuweisen
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['list-' . $pluginSignature]
        = 'content-onepage-renderer';

    // 4) New Content Element Wizard-Eintrag
    ExtensionManagementUtility::addPageTSConfig(<<<'TS'
mod.wizards.newContentElement.wizardItems.plugins {
  elements {
    onepagerenderer {
      iconIdentifier = content-onepage-renderer
      title = LLL:EXT:onepage_extension/Resources/Private/Language/locallang_db.xlf:plugin.onepage_renderer.title
      description = LLL:EXT:onepage_extension/Resources/Private/Language/locallang_db.xlf:plugin.onepage_renderer.description
      tt_content_defValues {
        CType = list
        list_type = __PLUGIN_SIGNATURE__
      }
    }
  }
  show := addToList(onepagerenderer)
}
TS
    );

    // Platzhalter durch echte Signatur ersetzen
    $GLOBALS['TYPO3_CONF_VARS']['BE']['pagetsconfig'] = str_replace(
        '__PLUGIN_SIGNATURE__',
        $pluginSignature,
        (string)($GLOBALS['TYPO3_CONF_VARS']['BE']['pagetsconfig'] ?? '')
    );
})();
