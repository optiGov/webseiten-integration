<?php

class OptiGov
{

    /**
     * Renders the widget if request is coming from a user. If the request is coming from a bot, return pre-rendered
     * and static HTML code for SEO reasons.
     *
     * @param string $path The path in the URL that should be used to render the widget.
     * @param string|array $config The path to the config file or the config itself.
     * @return void
     * @throws Exception
     */
    public static function renderWidget(string $path, string|array $config = "./optiGov.json"): void
    {
        // ensure that the path starts with a slash and does not end with a slash
        $path = '/' . trim($path, '/');

        // handle .htaccess creation
        OptiGovHtaccessProvider::createIfNotExists(static::getBaseDirectory());

        // load the config
        $config = OptiGovConfigProvider::load(static::getBaseDirectory(), $config, $path);

        // render widget
        OptiGovWidgetRenderer::render($config);
    }

    /**
     * Renders a single component of the widget.
     *
     * @param string $path The path in the URL that should be used to render the widget.
     * @param string|array $config The path to the config file or the config itself.
     * @param string $component The name of the component to render.
     * @param array $properties The properties to pass to the component.
     * @return void
     * @throws Exception
     */
    public static function renderComponent(string $path, string|array $config = "./optiGov.json", string $component = "suche", array $properties = []): void {
        // ensure that the path starts with a slash and does not end with a slash
        $path = '/' . trim($path, '/');

        // load the config
        $config = OptiGovConfigProvider::load(static::getBaseDirectory(), $config, $path);

        // check if the request is coming from a bot
        if(!OptiGovBotDetector::isRequestFromBot()){
            // request is coming from a user, render the widget
            OptiGovWidgetRenderer::renderComponent($config, $component, $properties);
        }
    }

    /**
     * Returns all entries from the api.
     *
     * @param string $path The path in the URL that should be used to render the widget.
     * @param string|array $config The path to the config file or the config itself.
     * @return array
     * @throws Exception
     */
    public static function getAllResources(string $path, string|array $config = "./optiGov.json"): array {
        // load the config
        $config = OptiGovConfigProvider::load(static::getBaseDirectory(), $config, $path);

        // return the resources
        return OptiGovApiClient::getAllEntries($config)["verwaltung"] ?? [];
    }

    /**
     * Returns the base directory of the library.
     *
     * @return string
     */
    private static function getBaseDirectory(): string {
        return dirname(__FILE__);
    }
}