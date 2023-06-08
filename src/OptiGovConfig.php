<?php

class OptiGovConfig
{

    /**
     * @var array
     */
    private array $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Returns a requested value from the config. Supports dot notation.
     */
    public function get(string $key, $default = null)
    {
        // split the key into parts
        $parts = explode('.', $key);

        // return null if the key is empty
        if (empty($parts)) {
            return null;
        }

        $data = $this->data;
        while (count($parts) > 0) {
            $part = array_shift($parts);

            // check if the key exists
            if (!array_key_exists($part, $data)) {
                // key does not exist, return the default value
                return $default;
            }

            // key exists, get the value
            $data = $data[$part];
        }

        // return the value
        return $data;
    }

    /**
     * Get the urls from the config.
     *
     * @return array
     */
    public function getUrls(): array
    {
        return $this->get('urls', []);
    }

    /**
     * Get the installation from the config.
     *
     * @return string
     */
    public function getInstallation(): string
    {
        return $this->get('installation', '');
    }

    /**
     * Get the VerwaltungId from the config.
     *
     * @return int
     */
    public function getVerwaltungId(): int
    {
        return $this->get('verwaltungId', 0);
    }

    /**
     * Returns the JSON representation of the config.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->data);
    }

}