<?php

class OptiGovWidgetRenderer {
    /**
     * Renders the widget.
     *
     * @param OptiGovConfig $config
     * @return void
     */
    public static function render(OptiGovConfig $config): void {
        // get the installation path
        $installation = $config->getInstallation();

        // add the widget container
        $html = <<<EOT
<!-- optiGov Widget -->
<div id="app"></div>
EOT;

        // add the widget style
        $html .= <<<EOT
<link rel="stylesheet" href="$installation/widget/style.css"/>
EOT;

        // add the widget script
        $html .= <<<EOT
<script type="module">    
import * as optiGov from "$installation/widget/optiGov.mjs"
optiGov.renderWidget({rootContainer: "#app",configuration: {$config->toJson()}})
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
    public static function renderComponent(OptiGovConfig $config, string $component, array $properties): void {
        // get the installation path
        $installation = $config->getInstallation();

        // add the component to the properties
        $properties = array_merge_recursive($properties, ["mode" => $component]);

        // convert the properties to JSON
        $properties = json_encode($properties);

        // add the widget container
        $html = <<<EOT
<!-- optiGov Widget -->
<div id="app"></div>
EOT;

        // add the widget style
        $html .= <<<EOT
<link rel="stylesheet" href="$installation/widget/style.css"/>
EOT;

        // add the widget script
        $html .= <<<EOT
<script type="module">    
import * as optiGov from "$installation/widget/optiGov.mjs"
optiGov.renderComponent({rootContainer: "#app",properties: {$properties},configuration: {$config->toJson()}})
</script>
EOT;

        // render the widget
        print $html;
    }
}