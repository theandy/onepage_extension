# OnePage Extension für TYPO3 12.4

## Ziel
Diese Extension ermöglicht OnePage-Websites, bei denen Redakteure einzelne **Abschnitte** als Seiten anlegen.  
Eine Startseite enthält das Plugin **Onepage Renderer**, das alle Abschnitts-Seiten automatisch in Reihenfolge ausgibt.

---

## Aktueller Stand (Oktober 2025)

- Es wird ein neuer **Doktype 170 – Onepage-Abschnitt** angelegt.  
  Seiten mit diesem Doktype werden automatisch als Abschnitte in der OnePage-Seite eingebunden.
- Für alle Seiten mit Doktype 170 steht in der Hauptnavigation ein zusätzliches Feld **`sectionLink`** zur Verfügung, 
  das auf die jeweilige Sektion (`#anchor`) verweist.
- Das Rendering erfolgt über das Extbase-Plugin **Onepage Renderer**.
- Alle Inhalte einer Abschnittsseite werden direkt im Frontend ausgegeben, 
  unabhängig von Spalten oder Inhaltstypen.
- Die Extension ist vollständig kompatibel mit TYPO3 12.4.

---

### Redaktions-Workflow

1. **Startseite:**  
   Enthält das Inhaltselement **„Onepage Renderer“** (Plugin).

2. **Abschnitt-Seiten:**  
   Unterhalb der Startseite anlegen mit Doktype **„Onepage-Abschnitt“ (170)**.  
   Inhalte in beliebige Spalten einfügen.

3. **Reihenfolge:**  
   Entspricht der Sortierung der Unterseiten.

4. **Ankerbildung:**  
   Automatisch aus `slug` (Fallback: `nav_title` oder `title`).

5. **Navigation:**  
   Bei Seiten mit Doktype 170 wird in der Navigation automatisch das Feld `sectionLink` erzeugt.  
   Dieses enthält den passenden Anker-Link (z. B. `#ueber-uns`) und steht auf gleicher Ebene wie `title`, `link`, `target`, etc.

---

### Technische Komponenten

#### Plugin
- Name: **Onepage Renderer**
- Typ: *LIST*-Plugin (`list_type = onepageextension_onepagerenderer`)
- Icon:  
  `Resources/Public/Icons/ce-onepage-renderer.svg`
- Wizard-Eintrag im Backend verfügbar

#### TypoScript
- Static Template eingebunden über Include-Sets  
  (`Configuration/TypoScript/constants.typoscript` und `setup.typoscript`)
- Enthält:
  - Plugin-ViewPaths
  - DataProcessor für Abschnitt-Seiten (`doktype=170`)
  - `lib.onepage_contentByPid` → rendert alle Inhalte der Abschnittsseite
  - `lib.onepage_renderContentByUid` → rendert Inhaltselemente nach UID

#### Controller
`Classes/Controller/OnepageController.php`
- Action: `renderAction(): ResponseInterface`
- Holt alle Abschnitt-Seiten (`doktype=170`) der aktuellen Seite
- Erzeugt Anker aus Slug oder Nav-Title
- Übergibt Daten an Fluid

#### Fluid Template
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

#### Navigation
`Classes/DataProcessing/OnepageAnchorProcessor.php`
- Prüft alle Menüeinträge.
- Wenn `doktype=170`, wird automatisch ein `sectionLink` (z. B. `#kontakt`) erzeugt.  
  Dieser steht in `{mainnavigationItem.sectionLink}` und kann direkt verwendet werden:
  ```html
  <a href="{mainnavigationItem.sectionLink ?? mainnavigationItem.link}">
      {mainnavigationItem.title}
  </a>
  ```

---

### Installation

1. Extension installieren oder ins `typo3conf/ext/` legen.  
2. Im Template-Modul das Static Template **„Onepage Extension“** einbinden.  
3. Startseite anlegen → Inhaltselement **„Onepage Renderer“** hinzufügen.  
4. Abschnitt-Seiten mit Doktype **170** unterhalb der Startseite erstellen.  
5. Inhalte auf Abschnitt-Seiten pflegen.  
6. Cache leeren → Frontend testen.

---

### Nächste Schritte

- Optional: Navigation/Scroll-Menü über Anchors generieren  
- Optional: Smooth-Scroll JS hinzufügen  
- Optional: Fallback-Layout für Nicht-Onepage-Seiten  
- Optional: Section-Templates über `backend_layout` oder `theme`-Feld auswählen  

---

**Version:** 1.0 (Entwicklungsstand)  
**Autor:** Andreas Löwer  
**TYPO3:** 12.4.x  
**Lizenz:** GPL-2.0-or-later
