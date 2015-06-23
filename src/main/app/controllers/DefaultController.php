<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * DefaultController.php
 * P14-helpdesk_hondsrug
 *
 * Default demo controller
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       21/06/2015
 */

class DefaultController extends MvcBaseController {
    /**
     * Renders the default 404 page
     * @param $args
     */
    public function render404($args) {
        header("HTTP/1.1 404 Not Found");

        echo "<h1>Not found</h1>";
        echo "The requested page was not found.";
    }
}