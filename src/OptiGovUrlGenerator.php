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
}