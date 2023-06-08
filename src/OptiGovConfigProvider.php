<?php

class OptiGovConfigProvider
{
    /**
     * Loads the config from the given path or config.
     *
     * @param string $baseDirectory
     * @param string|array $config
     * @param string $path
     * @return array
     * @throws Exception
     */
    public static function load(string $baseDirectory, string|array $config, string $path): OptiGovConfig
    {
        // check if the config is a string
        if (is_string($config)) {
            // config is a string, load the config from the given path
            $config = self::loadConfigFromFile($baseDirectory, $config);
        }

        // merge with default config and return the config
        $config = array_merge_recursive(self::getDefaultConfig($path), $config);

        // ensure that required config values are set
        self::ensureRequiredConfigValuesAreSet($config);

        // remove trailing slashes from the installation value if present
        $config['installation'] = rtrim($config['installation'], '/');

        // return the config
        return new OptiGovConfig($config);
    }

    /**
     * Loads the config from the given path.
     */
    private static function loadConfigFromFile(string $baseDirectory, string $configPath): array
    {
        // get the path to the config file
        $configPath = $baseDirectory . '/' . $configPath;

        // check if the config file exists
        if (!file_exists($configPath)) {
            // config file does not exist, throw an exception
            throw new Exception("Config file `$configPath` could not be found. Ensure it exists and is readable.");
        }

        // load the config file
        $config = json_decode(file_get_contents($configPath), true);

        // check if the config is valid
        if (!is_array($config)) {
            // config is not valid, throw an exception
            throw new Exception("Config file `$configPath` does not contain valid JSON.");
        }

        // return the config
        return $config;
    }

    /**
     * Returns the default config.
     *
     * @param string $path
     * @return array
     */
    private static function getDefaultConfig(string $path): array
    {
        return [
            "verwaltungId" => 1,
            "oauth" => [
                "clientId" => 2
            ],
            "urls" => [
                "startseite" => "$path/",
                "dienstleistung" => "$path/dienstleistung/:id",
                "einrichtung" => "$path/einrichtung/:id",
                "mitarbeiter" => "$path/mitarbeiter/:id",
                "themenfeld" => "$path/themenfeld/:id",
                "alleDienstleistungen" => "$path/alle-dienstleistungen",
                "alleEinrichtungen" => "$path/alle-einrichtungen",
                "alleMitarbeiter" => "$path/alle-mitarbeiter",
                "meinKontoDetails" => "$path/mein-konto-details",
                "meinKontoChats" => "$path/mein-konto-chats",
                "meinKontoChat" => "$path/mein-konto-chat/:id",
                "meinKontoAntraege" => "$path/mein-konto-antraege",
                "meinKontoTermine" => "$path/mein-konto-termine",
                "loginRedirect" => "$path/login",
                "antragEingereicht" => "$path/antrag-eingereicht",
            ],
            "services" => [
                "meet" => [
                    "enabled" => true
                ],
                "adressOMat" => [
                    "enabled" => false,
                ]
            ]
        ];
    }

    /**
     * Ensures that required config values are set.
     *
     * @param array $config
     * @return bool
     * @throws Exception
     */
    private static function ensureRequiredConfigValuesAreSet(array $config): bool {
        // only check for those keys that are not present in the default config

        // check if the `installation` is set
        if (!isset($config['installation'])) {
            // `installation` is not set, throw an exception
            throw new Exception('Config value `installation` is not set.');
        }

        // check if `services.adressOMat.enabled` is true and `services.adressOMat.key` is not set
        if ($config['services']['adressOMat']['enabled'] && !isset($config['services']['adressOMat']['key'])) {
            // `services.adressOMat.enabled` is not set, throw an exception
            throw new Exception('According to the config file the Adress-O-Mat should be used, but no config value for `services.adressOMat.key` is set.');
        }

        return true;
    }
}