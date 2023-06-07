# Webseiten-Integration

Dieses Repository soll die Einbindung des optiGov JavaScript-Widgets in eine Website drastisch vereinfachen. Zusätzlich
unterstützt diese kleine PHP-Bibliothek eine bessere Indizierung der Inhalte von optiGov durch Suchmaschinen, da bei
Anfrage durch diese direkt ein HTML-Snapshot der Seite zurückgegeben wird und nicht erst JavaScript ausgeführt werden
muss.

## Einleitung

Um das Hosting und die Einbindung sehr einfach zu halten werden alle Dateien mithilfe der `compile.php` kompiliert und
in der einzigen Datei `optiGov.php` zusammengefasst. Diese Datei ist dann die einzige
Ein clonen dieses Repositories ist aber nicht notwendig.
Eine stets aktuelle Version der Datei `optiGov.php` kann direkt von der optiGov-Doku heruntergelanden werden.

## Einbindung

Um z.B. den Bürgerservice unter `https://optistadt.de/de/buergerservice` zu installieren muss in dem
Ordner `/de/buergerservice` die kompilierte Datei `optiGov.php` (s.o.), die Konfigurationsdatei `optiGov.json` und
Ihre `index.php` liegen.

```bash
/optistadt.de
├── de
│   ├── buergerservice
│   │   ├── optiGov.php
│   │   ├── optiGov.json
│   │   └── index.php
```

Die Einbindung erfolgt durch das Inkludieren der Datei `optiGov.php` auf der `index.php`, welche hier den gesamten
Bürgerservice mit allen Routen enthält.

Sobald die Datei `optiGov.php` eingebunden ist, kann mit der Funktion `optiGov::renderWidget('/de/buergerservice')` das
Widget an der gewünschten Stelle eingebunden werden.

```php
<?php

// index.php
// ...

require_once __DIR__ . '/optiGov.php';
optiGov::renderWidget('/de/buergerservice');
```

**Bitte beachten Sie folgende Punkte:**

- Weicht das Installationsverzeichnis von dem obigen Beispiel ab, muss der Pfad entsprechend angepasst werden.
- Die Datei `optiGov.php` legt eine `.htaccess`-Datei an, welche alle Routen auf die `index.php` umleitet. Dies ist notwendig
  für die SPA-Funktionalität des Widgets. Sollte bereits eine `.htaccess` existieren, oder kann diese nicht angelegt werden muss diese um die entsprechenden
  Regeln erweitert werden:
  ```.htaaccess
  # Redirect all requests for non-existing files to the index.html
  RewriteEngine on
  RewriteCond %{REQUEST_URI} !^/js/
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ index.html?url=$1 [QSA,L]
  ```