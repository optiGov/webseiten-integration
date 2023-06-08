<?php

class OptiGovUrlMatcher {
    /**
     * Checks if the given url matches the given path respecting :id placeholders.
     *
     * @param string $url
     * @param string $path
     * @return bool
     */
    public static function match(string $url, string $path): bool
    {
        // remove trailing slashes from the url and path
        $url = rtrim($url, '/');
        $path = rtrim($path, '/');

        // check if the url is the same as the path
        if($url === $path) {
            return true;
        }

        // check if the url contains :id placeholders
        return !empty(self::getMatches($url, $path));
    }

    /**
     * Returns the matches of parameters in the given url and path.
     *
     * @param string $url
     * @param string $path
     * @return array
     */
    public static function getMatches(string $url, string $path): array
    {
        // remove trailing slashes from the url and path
        $url = rtrim($url, '/');
        $path = rtrim($path, '/');

        // check if the url contains :id placeholders
        if (str_contains($url, ':id')) {
            // url contains :id placeholders, replace them with a regex
            $url = str_replace(':id', '([0-9]+)', $url);
        }

        // check if the url matches the path
        if (preg_match('#^' . $url . '$#', $path, $matches)) {
            // url matches the path, return the matches of parameters
            $matches = array_slice($matches, 1);

            // if matched values are numeric, convert them to integers
            foreach ($matches as &$match) {
                if (is_numeric($match)) {
                    $match = (int) $match;
                }
            }

            return $matches;
        }

        // url does not match the path, return an empty array
        return [];
    }
}