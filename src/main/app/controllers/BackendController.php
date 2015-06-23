<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * BackendController.php
 * P14-helpdesk_hondsrug
 *
 * Frontend controller
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       23/06/2015
 */

require_once "LoggedInController.php";

class BackendController extends LoggedInController {
    /**
     * @var int Minimum required role to view pages in this controller
     */
    protected $requiredRole = 2;

    /**
     * Renders the backend dashboard view
     * @param $args array URL params
     */
    public function dashboard($args) {
        // Load all incidents
        $incident_model = $this->loadModel("IncidentModel");
        $this->data['incidents'] = $incident_model->allObjects();

        // Render the dashboard view
        $this->data['page'] = 'incidents';
        $this->renderView("backend/dashboard");
    }

}