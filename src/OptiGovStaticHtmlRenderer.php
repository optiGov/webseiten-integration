<?php

class OptiGovStaticHtmlRenderer
{
    private const ROUTE_STARTSEITE = "startseite";
    private const ROUTE_DIENSTLEISTUNG_INDEX = "alleDienstleistungen";
    private const ROUTE_EINRICHTUNG_INDEX = "alleEinrichtungen";
    private const ROUTE_MITARBEITER_INDEX = "alleMitarbeiter";
    private const ROUTE_DIENSTLEISTUNG_DETAIL = "dienstleistung";
    private const ROUTE_EINRICHTUNG_DETAIL = "einrichtung";
    private const ROUTE_MITARBEITER_DETAIL = "mitarbeiter";

    /**
     * Render the static HTML code depending on the given config and requested path.
     *
     * @param OptiGovConfig $config
     * @return void
     */
    public static function render(OptiGovConfig $config)
    {
        // get the requested path
        $requestPath = $_SERVER['REQUEST_URI'];

        // iterate over the urls and check if the requested path matches
        // consider :id placeholders
        foreach ($config->getUrls() as $routeName => $url) {
            // check if the url matches the requested path
            if (OptiGovUrlMatcher::match($url, $requestPath)) {
                // get the id
                $id = OptiGovUrlMatcher::getMatches($url, $requestPath)[0] ?? null;

                // check which route was matched
                $html = match ($routeName) {
                    self::ROUTE_STARTSEITE => self::renderStartseite($config),
                    self::ROUTE_DIENSTLEISTUNG_INDEX => self::renderDienstleistungIndex($config),
                    self::ROUTE_EINRICHTUNG_INDEX => self::renderEinrichtungIndex($config),
                    self::ROUTE_MITARBEITER_INDEX => self::renderMitarbeiterIndex($config),
                    self::ROUTE_DIENSTLEISTUNG_DETAIL => self::renderDienstleistung($config, $id),
                    self::ROUTE_EINRICHTUNG_DETAIL => self::renderEinrichtung($config, $id),
                    self::ROUTE_MITARBEITER_DETAIL => self::renderMitarbeiter($config, $id),
                };

                // render the html
                print $html;

                return;
            }
        }
    }

    /**
     * Render the startseite.
     *
     * @param OptiGovConfig $config
     * @return string
     */
    private static function renderStartseite(OptiGovConfig $config): string
    {
        // get the headers
        $headerBuergerservice = $config->get("texts.buergerservice", "Bürgerservice");
        $headerDienstleistungen = $config->get("texts.dienstleistungPlural", "Dienstleistungen");
        $headerEinrichtungen = $config->get("texts.einrichtungPlural", "Einrichtungen");
        $headerMitarbeiter = $config->get("texts.mitarbeiterPlural", "Mitarbeiter");

        // compose the html
        $html = "<h1>$headerBuergerservice</h1>";

        // add links to index pages
        $url = OptiGovUrlGenerator::generateDienstleistungIndexUrl($config);
        $html .= "<div><a href='$url'>Alle {$headerDienstleistungen}</a></div>";

        $url = OptiGovUrlGenerator::generateEinrichtungIndexUrl($config);
        $html .= "<div><a href='$url'>Alle {$headerEinrichtungen}</a></div>";

        $url = OptiGovUrlGenerator::generateMitarbeiterIndexUrl($config);
        $html .= "<div><a href='$url'>Alle {$headerMitarbeiter}</a></div>";

        return $html;
    }

    /**
     * Render the Dienstleistung index page.
     *
     * @param OptiGovConfig $config
     * @return string
     */
    private static function renderDienstleistungIndex(OptiGovConfig $config): string
    {
        // get the data
        $data = OptiGovApiClient::getAllDienstleistungEntries($config);

        // get the header
        $headerDienstleistungen = $config->get("texts.dienstleistungPlural", "Dienstleistungen");

        $html = "<h1>Alle $headerDienstleistungen</h1><ul>";
        // iterate over $data["dienstleistungen"]
        foreach ($data as $dienstleistung) {
            ;
            // get the url
            $url = OptiGovUrlGenerator::generateDienstleistungUrl($config, $dienstleistung["id"]);

            // add the link to the html
            $html .= "<li><a href='$url'>{$dienstleistung["leistungsname"]}</a><br></li>";
        }

        $html .= "</ul>";

        return $html;
    }

    /**
     * Render the Einrichtung index page.
     *
     * @param OptiGovConfig $config
     * @return string
     */
    private static function renderEinrichtungIndex(OptiGovConfig $config): string
    {
        // get the data
        $data = OptiGovApiClient::getAllEinrichtungEntries($config);

        // get the header
        $headerEinrichtungen = $config->get("texts.einrichtungPlural", "Einrichtungen");

        $html = "<h1>Alle $headerEinrichtungen</h1><ul>";
        // iterate over $data
        foreach ($data as $einrichtung) {
            // get the url
            $url = OptiGovUrlGenerator::generateEinrichtungUrl($config, $einrichtung["id"]);

            // add the link to the html
            $html .= "<li><a href='$url'>{$einrichtung["name"]}</a><br></li>";
        }

        $html .= "</ul>";

        return $html;
    }

    /**
     * Render the Mitarbeiter index page.
     *
     * @param OptiGovConfig $config
     * @return string
     */
    private static function renderMitarbeiterIndex(OptiGovConfig $config): string
    {
        // get the data
        $data = OptiGovApiClient::getAllMitarbeiterEntries($config);

        // get the header
        $headerMitarbeiter = $config->get("texts.mitarbeiterPlural", "Mitarbeiter");

        $html = "<h1>Alle $headerMitarbeiter</h1><ul>";
        // iterate over $data["mitarbeiter"]
        foreach ($data as $mitarbeiter) {
            // get the url
            $url = OptiGovUrlGenerator::generateMitarbeiterUrl($config, $mitarbeiter["id"]);

            // add the link to the html
            $html .= "<li><a href='$url'>{$mitarbeiter["name"]}</a><br></li>";
        }

        $html .= "</ul>";

        return $html;
    }

    /**
     * Render a Dienstleistung entry.
     *
     * @param OptiGovConfig $config
     * @param int $id
     * @return string
     */
    private static function renderDienstleistung(OptiGovConfig $config, int $id): string
    {
        // get the data
        $data = OptiGovApiClient::getDienstleistung($config, $id);

        // get the variables
        $leistungsname = $data["leistungsname"] ?? null;
        $synonyme = $data["begriffe_im_kontext"] ?? null;
        $kurztext = $data["kurztext"] ?? null;
        $volltext = $data["volltext"] ?? null;
        $hinweise = $data["hinweise"] ?? null;

        // edit head
        static::setHeadTitle($leistungsname);
        static::setHeadMetaDescription($kurztext);
        static::setHeadKeywords($synonyme);

        // compose the html
        $html = "<h1>$leistungsname</h1>";
        $html .= $volltext !== null ? "<div><h2>Details</h2><div>$volltext</div></div>" : "";
        $html .= $hinweise !== null ? "<div><h2>Hinweise</h2><div>$hinweise</div></div>" : "";

        return $html;
    }

    /**
     * Render an Einrichtung entry.
     *
     * @param OptiGovConfig $config
     * @param int $id
     * @return string
     */
    private static function renderEinrichtung(OptiGovConfig $config, int $id): string
    {
        // get the data
        $data = OptiGovApiClient::getEinrichtung($config, $id);

        // get the variables
        $name = $data["name"] ?? null;
        $beschreibung = $data["beschreibung"] ?? null;
        $standort = null;
        if ($data["gebaeude"] != null) {
            $standort = ($data["gebaeude"]["name"] ?? null) . "<br>"
                . ($data["gebaeude"]["strasse"] ?? null) . " " . ($data["gebaeude"]["hausnummer"] ?? null) . "<br>"
                . ($data["gebaeude"]["plz"] ?? null) . " " . ($data["gebaeude"]["ort"] ?? null);
        }

        // edit head
        static::setHeadTitle($name);
        static::setHeadMetaDescription($beschreibung);

        // compose the html
        $html = "<h1>$name</h1>";
        $html .= $beschreibung != null ? "<div><h2>Beschreibung</h2><div>$beschreibung</div></div>" : "";
        $html .= $standort != null ? "<div><h2>Standort</h2><div>$standort</div></div>" : "";

        return $html;
    }

    /**
     * Render a Mitarbeiter entry.
     *
     * @param OptiGovConfig $config
     * @param int $id
     * @return string
     */
    private static function renderMitarbeiter(OptiGovConfig $config, int $id): string
    {
        // get the data
        $data = OptiGovApiClient::getMitarbeiter($config, $id);

        // get the variables
        $name = $data["name"] ?? null;
        $raum = $data["raum"] ?? null;

        // edit head
        static::setHeadTitle($name);

        // compose the html
        $html = "<h1>$name</h1>";
        $html .= $raum != null ? "<div><h2>Raum</h2><div>$raum</div></div>" : "";

        return $html;
    }

    /**
     * Set the meta description tag with JavaScript.
     *
     * @param string|null $description
     * @return void
     */
    private static function setHeadMetaDescription(string|null $description): void
    {
        // if there is no description, do nothing
        if($description === null) {
            return;
        }

        // strip tags
        $description = strip_tags($description);

        // print js code
        print <<<EOF
        <script>
            (function(){   
                let metaElement = document.querySelector("meta[name='description']");
                if(metaElement === null) {
                    let headElement = document.querySelector("head");
                    metaElement = document.createElement("meta");
                    headElement.appendChild(metaElement);
                }
                metaElement.setAttribute("name", "description");
                metaElement.setAttribute("content", "$description");
            })()
        </script>   
EOF;
    }

    /**
     * Sets the page title with JavaScript.
     *
     * @param string|null $title
     */
    private static function setHeadTitle(string|null $title): void
    {
        // if there is no title, do nothing
        if($title === null) {
            return;
        }

        // print js code
        print <<<EOF
        <script>
            document.title = "$title";
        </script>
EOF;
    }

    /**
     * Sets the page keywords with JavaScript.
     *
     * @param string|null $keywords
     */
    private static function setHeadKeywords(string|null $keywords): void
    {
        // if there are no keywords, do nothing
        if($keywords === null) {
            return;
        }

        // print js code
        print <<<EOF
        <script>
            (function(){   
                let metaElement = document.querySelector("meta[name='keywords']");
                if(metaElement === null) {
                    let headElement = document.querySelector("head");
                    metaElement = document.createElement("meta");
                    headElement.appendChild(metaElement);
                }
                metaElement.setAttribute("name", "keywords");
                metaElement.setAttribute("content", "$keywords");
            })()
        </script>
EOF;
    }
}