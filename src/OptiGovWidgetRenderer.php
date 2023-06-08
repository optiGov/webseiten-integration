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
}