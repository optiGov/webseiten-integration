# Webseiten-Integration

Dieses Repository soll die Einbindung des optiGov JavaScript-Widgets in eine Website drastisch vereinfachen. Zusätzlich
unterstützt diese kleine PHP-Bibliothek eine bessere Indizierung der Inhalte von optiGov durch Suchmaschinen, da bei
Anfrage durch diese direkt ein HTML-Snippet der angefragten Seite zurückgegeben wird und nicht erst JavaScript
ausgeführt werden
muss.

## Einleitung

Um das Hosting und die Einbindung sehr einfach zu halten werden alle Dateien mithilfe der `compile.php` kompiliert und
in der einzigen Datei `optiGov.php` zusammengefasst.

Ein klonen dieses Repositories und eigenes Kompilieren ist aber nicht notwendig.
Eine stets aktuelle Version der Datei `optiGov.php` kann direkt hier als Release heruntergeladen werden:

<kbd><br>[Aktuelle `optiGov.php`-Datei herunterladen](https://github.com/optigov/webseiten-integration/releases/latest/download/optiGov.php)<br> </kbd>

## Einbindung

### Dateistruktur

Um z.B. den Bürgerservice unter `https://optistadt.de/de/buergerservice` zu installieren muss in dem
Ordner `/de/buergerservice` die kompilierte Datei `optiGov.php` (s.o.), die Konfigurationsdatei `optiGov.json` 
(s. [Konfiguration](#konfiguration)) und Ihre `index.php`-Datei (die Inhaltsseite Ihrer Website) liegen.

```bash
/optistadt.de
├── de
│   ├── buergerservice
│   │   ├── optiGov.php
│   │   ├── optiGov.json
│   │   └── index.php
```

Die Einbindung erfolgt durch das Inkludieren der Datei `optiGov.php` auf der `index.php`, welche hier den gesamten
Bürgerservice mit allen Routen enthält (s. [Verwendung](#verwendung)).

### Konfiguration

Die Datei `optiGov.json` enthält die Konfiguration für das Widget. Diese wird allerdings automatisch um alle fehlenden
Werte ergänzt, sodass nur die Minimalkonfiguration enthalten sein muss. Dies beinhaltet u.a. alle URLs zu den einzelnen
Seiten - diese werden automatisch bestimmt. Aus diesem Grund wird es in den meisten Fällen ausreichen, wenn Sie
lediglich die Installations-URL in der Konfigurationsdatei angeben.

```json
{
  "installation": "https://demo.optigov.de"
}
```

Natürlich können Sie auch alle anderen Werte manuell angeben. Alle möglichen Werte und deren Bedeutung finden Sie in der
Dokumentation auf der Seite der [JavaScript-Widget Anbindung](https://doku.optigov.de/javascript-widget/anbindung).

## Verwendung

Sobald die Datei `optiGov.php` eingebunden ist, kann mit der Funktion `optiGov::renderWidget('/de/buergerservice')` das
Widget an der gewünschten Stelle eingebunden werden.

```php
<?php

// index.php
// ...

require_once __DIR__ . '/optiGov.php';
optiGov::renderWidget('/de/buergerservice');
```

### Komponenten-Modus

Möchten Sie nur einzelne Komponenten des Widgets verwenden, können Sie diese mit der
Funktion `optiGov::renderComponent(...)`
einbinden. Diese Funktion erwartet als Parameter den Namen der Komponente und ggf. ein Objekt an Eigenschaften. Eine Liste aller verfügbaren Komponenten und
deren Namen finden Sie in der Dokumentation auf der Seite
der [JavaScript-Widget Anbindung](https://doku.optigov.de/javascript-widget/anbindung).

```php
<?php

// index.php
// ...

require_once __DIR__ . '/optiGov.php';
optiGov::renderComponent(/'de/buergerservice', component: 'mitarbeiter', properties: ['id' => 330]);
```

## Hinweise

Bitte beachten Sie folgende Hinweise:

- Weicht das Installationsverzeichnis von dem obigen Beispiel ab, muss der Pfad entsprechend angepasst werden.
- Weicht der Pfad der Konfigurationsdatei von dem obigen Beispiel ab, können Sie diesen als zweiten Parameter an die
  Funktionen der Klasse `optiGov` übergeben. Alternativ können Sie auch die Konfiguration direkt als Array übergeben.
- Die Datei `optiGov.php` legt eine `.htaccess`-Datei an, welche alle Routen auf die `index.php` umleitet. Dies ist
  notwendig
  für die SPA-Funktionalität des Widgets. Sollte bereits eine `.htaccess` existieren, oder kann diese nicht angelegt
  werden muss diese um die entsprechenden
  Regeln erweitert werden:
  ```.htaaccess
  # Redirect all requests for non-existing files to the index.html
  RewriteEngine on
  RewriteCond %{REQUEST_URI} !^/js/
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
  ```