# OnePage Extension für TYPO3 12.4

## Ziel
Diese Extension ermöglicht OnePage-Websites, bei denen Redakteure einzelne **Abschnitte** als Seiten anlegen.  
Eine Startseite enthält das Plugin **Onepage Renderer**, das alle Abschnitts-Seiten automatisch in Reihenfolge ausgibt.

---

## Aktueller Stand (Oktober 2025)

### 1. Allgemeines
- Extension-Key: `onepage_extension`
- Namespace: `AndreasLoewer\OnepageExtension`
- Kompatibel mit TYPO3 **12.4**
- Keine eigenen Datenbanktabellen
- Redakteure pflegen Inhalte über Seitenstruktur

---

### 2. Funktionsweise

#### a) Doktype
- Neuer Doktype: **170 – Onepage-Abschnitt**
- Registriert in:  
  `Configuration/TCA/Overrides/pages.php`
- Icon:  
  `Resources/Public/Icons/doktype-onepage-section.svg`

#### b) Plugin
- Name: **Onepage Renderer**
- Typ: *LIST*-Plugin (`list_type = onepageextension_onepagerenderer`)
- Registriert in:  
  - `ext_localconf.php`
  - `Configuration/TCA/Overrides/tt_content.php`
- Icon:  
  `Resources/Public/Icons/ce-onepage-renderer.svg`
- Wizard-Eintrag im Backend verfügbar

#### c) TypoScript
- Static Template eingebunden über Include-Sets  
  (`Configuration/TypoScript/constants.typoscript` und `setup.typoscript`)
- Setup enthält:
  - Plugin-ViewPaths
  - DataProcessor für Abschnitt-Seiten (`doktype=170`)
  - `lib.onepage_contentByPid` → rendert alle Inhalte der Abschnittsseite
- Mapping:
  ```typoscript
  tt_content.list.20.onepageextension_onepagerenderer = USER
  tt_content.list.20.onepageextension_onepagerenderer {
      userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
      extensionName = OnepageExtension
      pluginName = OnepageRenderer
      vendorName = AndreasLoewer
  }
  ```

#### d) Controller
`Classes/Controller/OnepageController.php`
- Action: `renderAction(): ResponseInterface`
- Holt alle Abschnitt-Seiten (`doktype=170`) der aktuellen Seite
- Erzeugt Anchor-ID aus Slug oder Nav-Title
- Übergibt Seiten + Inhalte an Fluid-Template

#### e) Fluid Template
`Resources/Private/Templates/Onepage/Render.html`
- Rendert für jede Abschnitt-Seite:
  ```html
  <section id="{section.anchor}">
      <header><h2>{section.title}</h2></header>
      <div class="onepage-section-content">
          <f:cObject typoscriptObjectPath="lib.onepage_contentByPid" data="{pid: section.uid}" />
      </div>
  </section>
  ```
- Gibt **alle Inhalte** der Abschnitts-Seite aus (Text, Container, Plugins etc.)

---

### 3. Redaktions-Workflow

1. **Startseite:**  
   Enthält das Inhaltselement **„Onepage Renderer“** (Plugin).

2. **Abschnitt-Seiten:**  
   Unterhalb der Startseite anlegen mit Doktype **„Onepage-Abschnitt“ (170)**.  
   Inhalte in beliebige Spalten einfügen.

3. **Reihenfolge:**  
   Entspricht der Sortierung der Unterseiten.

4. **Ankerbildung:**  
   Automatisch aus `slug` (Fallback: `nav_title` oder `title`).

---

### 4. Dateien

| Datei | Funktion |
|-------|-----------|
| `ext_localconf.php` | Plugin-Registrierung |
| `ext_tables.php` | Static Template + Icon |
| `Configuration/TCA/Overrides/pages.php` | Doktype-Definition |
| `Configuration/TCA/Overrides/tt_content.php` | Plugin-TCA + Wizard |
| `Configuration/TypoScript/setup.typoscript` | Rendering-Logik |
| `Classes/Controller/OnepageController.php` | Hauptlogik |
| `Resources/Private/Templates/Onepage/Render.html` | Ausgabe-Template |
| `Resources/Public/Icons/*` | Icons |

---

### 5. Installation

1. Extension installieren oder ins `typo3conf/ext/` legen.  
2. Im Template-Modul das Static Template **„Onepage Extension“** einbinden.  
3. Startseite anlegen → Inhaltselement **„Onepage Renderer“** hinzufügen.  
4. Abschnitt-Seiten mit Doktype **170** unterhalb der Startseite erstellen.  
5. Inhalte auf Abschnitt-Seiten pflegen.  
6. Cache leeren → Frontend testen.

---

### 6. Nächste Schritte

- Optional: Navigation/Scroll-Menü über Anchors generieren  
- Optional: Smooth-Scroll JS hinzufügen  
- Optional: Fallback-Layout für Nicht-Onepage-Seiten  
- Optional: Section-Templates über `backend_layout` oder `theme`-Feld auswählen  

---

**Version:** 1.0 (Entwicklungsstand)  
**Autor:** Andreas Löwer  
**TYPO3:** 12.4.x  
**Lizenz:** GPL-2.0-or-later
