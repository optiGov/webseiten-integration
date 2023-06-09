<?php

class OptiGovStaticHtmlRenderer
{
    private const ROUTE_STARTSEITE = "startseite";
    private const ROUTE_DIENSTLEISTUNG = "dienstleistung";
    private const ROUTE_EINRICHTUNG = "einrichtung";
    private const ROUTE_MITARBEITER = "mitarbeiter";

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
                    self::ROUTE_STARTSEITE => self::getStartseite($config),
                    self::ROUTE_DIENSTLEISTUNG => self::renderDienstleistung($config, $id),
                    self::ROUTE_EINRICHTUNG => self::renderEinrichtung($config, $id),
                    self::ROUTE_MITARBEITER => self::renderMitarbeiter($config, $id),
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
    private static function getStartseite(OptiGovConfig $config): string
    {
        // get the data
        $data = OptiGovApiClient::getAllEntries($config)["verwaltung"] ?? null;

        // get the headers
        $headerBuergerservice = $config->get("texts.buergerservice", "Bürgerservice");
        $headerDienstleistungen = $config->get("texts.dienstleistungPlural", "Dienstleistungen");
        $headerEinrichtungen = $config->get("texts.einrichtungPlural", "Einrichtungen");
        $headerMitarbeiter = $config->get("texts.mitarbeiterPlural", "Mitarbeiter");

        // compose the html
        $html = "<h1>$headerBuergerservice</h1>";

        $html .= "<h2>Alle $headerDienstleistungen</h2><ul>";
        // iterate over $data["dienstleistungen"]
        foreach ($data["dienstleistungen"] as $dienstleistung) {
            ;
            // get the url
            $url = OptiGovUrlGenerator::generateDienstleistungUrl($config, $dienstleistung["id"]);

            // add the link to the html
            $html .= "<li><a href='$url'>{$dienstleistung["leistungsname"]}</a><br></li>";
        }

        $html .= "</ul><h2>Alle $headerEinrichtungen</h2><ul>";
        // iterate over $data["einrichtungen"]
        foreach ($data["einrichtungen"] as $einrichtung) {
            // get the url
            $url = OptiGovUrlGenerator::generateEinrichtungUrl($config, $einrichtung["id"]);

            // add the link to the html
            $html .= "<li><a href='$url'>{$einrichtung["name"]}</a><br></li>";
        }

        $html .= "</ul><h2>Alle $headerMitarbeiter</h2><ul>";
        // iterate over $data["mitarbeiter"]
        foreach ($data["mitarbeiter"] as $mitarbeiter) {
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