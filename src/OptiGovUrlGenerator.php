<?php

class OptiGovUrlGenerator {
    /**
     * Generates a url for a Dienstleistung entry.
     *
     * @param OptiGovConfig $config
     * @param int $id
     * @return string
     */
    public static function generateDienstleistungUrl(OptiGovConfig $config, int $id): string
    {
        // replace the :id placeholder with the given id
        return str_replace(':id', $id, $config->get('urls.dienstleistung'));
    }

    /**
     * Generates a url for the Dienstleistung index page.
     *
     * @param OptiGovConfig $config
     * @return string
     */
    public static function generateDienstleistungIndexUrl(OptiGovConfig $config): string
    {
        return $config->get('urls.alleDienstleistungen');
    }

    /**
     * Generates a url for a Einrichtung entry.
     *
     * @param OptiGovConfig $config
     * @param int $id
     * @return string
     */
    public static function generateEinrichtungUrl(OptiGovConfig $config, int $id): string
    {
        // replace the :id placeholder with the given id
        return str_replace(':id', $id, $config->get('urls.einrichtung'));
    }

    /**
     * Generates a url for the Einrichtung index page.
     *
     * @param OptiGovConfig $config
     * @return string
     */
    public static function generateEinrichtungIndexUrl(OptiGovConfig $config): string
    {
        return $config->get('urls.alleEinrichtungen');
    }

    /**
     * Generates a url for a Mitarbeiter entry.
     *
     * @param OptiGovConfig $config
     * @param int $id
     * @return string
     */
    public static function generateMitarbeiterUrl(OptiGovConfig $config, int $id): string
    {
        // replace the :id placeholder with the given id
        return str_replace(':id', $id, $config->get('urls.mitarbeiter'));
    }

    /**
     * Generates a url for the Mitarbeiter index page.
     *
     * @param OptiGovConfig $config
     * @return string
     */
    public static function generateMitarbeiterIndexUrl(OptiGovConfig $config): string
    {
        return $config->get('urls.alleMitarbeiter');
    }
}