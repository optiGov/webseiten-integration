<?php

class OptiGovHtaccessProvider
{
    /**
     * Creates the .htaccess file if it does not exist yet.
     */
    public static function createIfNotExists(string $baseDirectory): void
    {
        // get the path to the .htaccess file
        $htaccessPath = $baseDirectory . '/.htaccess';

        // check if the .htaccess file exists
        if (!file_exists($htaccessPath)) {
            // .htaccess file does not exist, create it
            file_put_contents($htaccessPath, self::getHtaccessContent());
        }
    }

    /**
     * Returns the content of the .htaccess file.
     */
    private static function getHtaccessContent(): string
    {
        return <<<EOT
# BEGIN optiGov
<IfModule mod_rewrite.c>
    # Redirect all requests for non-existing files to the index.php
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>
# END optiGov
EOT;
    }

}