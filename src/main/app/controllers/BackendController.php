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

    /**
     * Renders the incident detail view
     * @param $args URL params
     */
    public function incident($args) {
        // Load the incident
        $incident_model = $this->loadModel("IncidentModel");
        $this->data['incident'] = $incident_model->getObjectByPk($args[1]);

        // Show 404 when no incident was found
        if($this->data['incident'] === false)
            $this->MvcInstance->dieWithDebugMessageOr404(
                "Incident not found",
                array("URL arguments" => $args)
            );

        // Render the incident detail view
        $this->data['page'] = 'incidents';
        $this->renderView("backend/detail_incident");
    }

    public function incidentDelete($args) {
        // Load the incident
        $incident_model = $this->loadModel("IncidentModel");
        $incident = $incident_model->getObjectByPk($args[1]);

        // Show 404 when no incident was found
        if($incident === false)
            $this->MvcInstance->dieWithDebugMessageOr404(
                "Incident not found",
                array("URL arguments" => $args)
            );

        // Delete the incident
        $incident_model->deleteWithPk($args[1]);

        // Redirect back to the dashboard
        $this->redirectToUrl("/backend");
    }

}