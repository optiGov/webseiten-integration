<?php

class OptiGovApiClient
{
    /**
     * Makes a GraphQL request to the OptiGov API.
     *
     * @param OptiGovConfig $config
     * @param string $query
     * @param array $variables
     * @return array|null
     */
    private static function request(OptiGovConfig $config, string $query, array $variables = []): ?array
    {
        // get the endpoint
        $endpoint = "{$config->getInstallation()}/api";

        // create the request body
        $body = [
            "query" => $query,
            "variables" => $variables
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'User-Agent: optigov/webseiten-integration',
                'Content-Type: application/json;charset=utf-8'
            )
        );

        $response = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        try {
            return json_decode($response, true)["data"] ?? null;
        } catch (Exception $e) {
            return [];
        }

    }

    /**
     * Returns all Dienstleistung, Einrichtung and Mitarbeiter entries.
     *
     * @param OptiGovConfig $config
     * @return array
     */
    public static function getAllEntries(OptiGovConfig $config): array
    {
        // build the query
        $query = <<<EOT
query {
    verwaltung(id: {$config->getVerwaltungId()}){
        dienstleistungen{
            id
            leistungsname
        }
        einrichtungen{
            id
            name
        }
        mitarbeiter{
            id
            name
        }
    }
}
EOT;

        return self::request($config, $query);
    }

    /**
     * Returns all Dienstleistung entries.
     *
     * @param OptiGovConfig $config
     * @return array
     */
    public static function getAllDienstleistungEntries(OptiGovConfig $config): array
    {
        // build the query
        $query = <<<EOT
query {
    verwaltung(id: {$config->getVerwaltungId()}){
        dienstleistungen{
            id
            leistungsname
        }
    }
}
EOT;

        return self::request($config, $query)["verwaltung"]["dienstleistungen"] ?? [];
    }

    /**
     * Returns all Einrichtung entries.
     *
     * @param OptiGovConfig $config
     * @return array
     */
    public static function getAllEinrichtungEntries(OptiGovConfig $config): array
    {
        // build the query
        $query = <<<EOT
query {
    verwaltung(id: {$config->getVerwaltungId()}){
        einrichtungen{
            id
            name
        }
    }
}
EOT;

        return self::request($config, $query)["verwaltung"]["einrichtungen"] ?? [];
    }

    /**
     * Returns all Mitarbeiter entries.
     *
     * @param OptiGovConfig $config
     * @return array
     */
    public static function getAllMitarbeiterEntries(OptiGovConfig $config): array
    {
        // build the query
        $query = <<<EOT
query {
    verwaltung(id: {$config->getVerwaltungId()}){
        mitarbeiter{
            id
            name
        }
    }
}
EOT;

        return self::request($config, $query)["verwaltung"]["mitarbeiter"] ?? [];
    }

    /**
     * Returns a specific Dienstleistung entry.
     *
     * @param OptiGovConfig $config
     * @param int $id
     * @return array
     */
    public static function getDienstleistung(OptiGovConfig $config, int $id): array
    {
        // build the query
        $query = <<<EOT
query {
    dienstleistung(id: {$id}){
            leistungsname
            begriffe_im_kontext
            kurztext
            volltext
            hinweise
    }
}
EOT;

        return self::request($config, $query)["dienstleistung"] ?? [];
    }

    /**
     * Returns a specific Einrichtung entry.
     *
     * @param OptiGovConfig $config
     * @param int $id
     * @return array
     */
    public static function getEinrichtung(OptiGovConfig $config, int $id): array
    {
        // build the query
        $query = <<<EOT
query {
    einrichtung(id: {$id}){
            name
            beschreibung
            gebaeude {
                name
                strasse
                hausnummer
                plz
                ort
            }
    }
}
EOT;

        return self::request($config, $query)["einrichtung"] ?? [];
    }

    /**
     * Returns a specific Mitarbeiter entry.
     *
     * @param OptiGovConfig $config
     * @param int $id
     * @return array
     */
    public static function getMitarbeiter(OptiGovConfig $config, int $id): array
    {
        // build the query
        $query = <<<EOT
query {
    mitarbeiter(id: {$id}){
            name
            raum
            servicezeiten
    }
}
EOT;

        return self::request($config, $query)["mitarbeiter"] ?? [];
    }
}