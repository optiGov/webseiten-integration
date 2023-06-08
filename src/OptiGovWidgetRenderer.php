<?php

class OptiGovWidgetRenderer
{
    /**
     * Renders the widget.
     *
     * @param OptiGovConfig $config
     * @return void
     */
    public static function render(OptiGovConfig $config): void
    {
        // get the installation path
        $installation = $config->getInstallation();

        // add the widget container, the style and get the ID of the container
        $containerId = static::renderContainer($config);

        // add the widget script
        $html = <<<EOT
<script type="module">    
import * as optiGov from "$installation/widget/optiGov.mjs"
optiGov.renderWidget({rootContainer: "#{$containerId}",configuration: {$config->toJson()}})
</script>
EOT;

        // render the widget
        print $html;
    }

    /**
     * Renders a single component of the widget.
     *
     * @param OptiGovConfig $config
     * @param string $component
     * @param array $properties
     * @return void
     */
    public static function renderComponent(OptiGovConfig $config, string $component, array $properties): void
    {
        // get the installation path
        $installation = $config->getInstallation();

        // add the component to the properties
        $properties = array_merge_recursive($properties, ["mode" => $component]);

        // convert the properties to JSON
        $properties = json_encode($properties);

        // add the widget container, the style and get the ID of the container
        $containerId = static::renderContainer($config);

            // add the widget script
        $html = <<<EOT
<script type="module">    
import * as optiGov from "$installation/widget/optiGov.mjs"
optiGov.renderComponent({rootContainer: "#{$containerId}",properties: {$properties},configuration: {$config->toJson()}})
</script>
EOT;

        // render the widget
        print $html;
    }

    /**
     * Renders the HTML container and CSS styles for the widget.
     * Returns the ID of the container.
     *
     * @param OptiGovConfig $config
     * @return string
     */
    public static function renderContainer(OptiGovConfig $config): string
    {
        // get the installation path
        $installation = $config->getInstallation();

        // generate a random ID for the container
        $containerId = uniqid("optiGovWidgetContainer_");

        // add the widget container
        $html = <<<EOT
<!-- optiGov Widget -->
<div id="{$containerId}"></div>
EOT;

        // add the widget style
        $html .= <<<EOT
<link rel="stylesheet" href="$installation/widget/style.css"/>
EOT;

        // render the html
        print $html;

        // return the ID of the container
        return $containerId;
    }
}