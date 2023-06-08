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

        // get base directory of the library
        $baseDirectory = dirname(__FILE__);

        // handle .htaccess creation
        OptiGovHtaccessProvider::createIfNotExists($baseDirectory);

        // load the config
        $config = OptiGovConfigProvider::load($baseDirectory, $config, $path);

        // check if the request is coming from a bot
        if(!OptiGovBotDetector::isRequestFromBot()){
            // request is coming from a user, render the widget
            OptiGovWidgetRenderer::render($config);
        }else{
            // request is coming from a bot, render the static HTML code
            OptiGovStaticHtmlRenderer::render($config);
        }
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

        // get base directory of the library
        $baseDirectory = dirname(__FILE__);

        // load the config
        $config = OptiGovConfigProvider::load($baseDirectory, $config, $path);

        // check if the request is coming from a bot
        if(!OptiGovBotDetector::isRequestFromBot()){
            // request is coming from a user, render the widget
            OptiGovWidgetRenderer::renderComponent($config, $component, $properties);
        }
    }
}