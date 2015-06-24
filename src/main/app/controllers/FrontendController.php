<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * FrontendController.php
 * P14-helpdesk_hondsrug
 *
 * Frontend controller
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       23/06/2015
 */

require_once "LoggedInController.php";

class FrontendController extends LoggedInController {
    /**
     * @var int Minimum required role to view pages in this controller
     */
    protected $requiredRole = 1;

    /**
     * Renders the frontend dashboard view
     * @param $args array URL params
     */
    public function dashboard($args) {
        // Load all components
        $hc_model = $this->loadModel("HardwareComponentModel");
        $this->data['componentlist'] = $hc_model->allObjects();

        // Load all questionnaires
        $questionnaire_model = $this->loadModel("QuestionnaireModel");
        $this->data['questionnaires'] = $questionnaire_model->allObjects();

        // Hand off the incident creation
        $this->handleIncidentCreation();

        // Render the dashboard view
        $this->renderView("frontend/dashboard");
    }

    /**
     * Renders the frontend questionnaire view
     * @param $args array URL params
     */
    public function questionnaire($args) {
        // Fetch URL params
        $questionnaire_id = $args[1];

        // Load all components
        $hc_model = $this->loadModel("HardwareComponentModel");
        $this->data['componentlist'] = $hc_model->allObjects();

        // Load the Questionnaire model and load the questionnaire
        $questionnaire_model = $this->loadModel("QuestionnaireModel");
        $this->data['questionnaire'] = $questionnaire_model->getObjectByPk($questionnaire_id);

        // Load the Questionnaire Entry model and the questionnaire entries
        $questionnaire_model = $this->loadModel("QuestionnaireEntryModel");
        $this->data['questionnaire_entries'] = $questionnaire_model->getEntriesForQuestionnaire($questionnaire_id);

        // Hand off the incident creation
        $this->handleIncidentCreation();

        // Render the questionnaire detail view
        $this->renderView("frontend/questionnaire");
    }

    /**
     * Checks for post data for incident creation and, when available, creates a
     * new incident with the given information. If the incident data was invalid,
     * it will set the viewdata's incident_error entry to a more detailed error message.
     */
    private function handleIncidentCreation() {
        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_incident"])) {
            // Set up component ID list
            $component_ids = array();
            foreach($this->data['componentlist'] as $component)
                $component_ids[] = $component['identificatiecode'];

            // Check data input
            if(!isset($_POST["component"]) || !isset($_POST["short_description"]) || !isset($_POST["description"]))
                $this->data['incident_error'] = "U heeft niet alle velden ingevuld.";

            else if(empty($_POST["component"]) || empty($_POST["short_description"]) || empty($_POST["description"]))
                $this->data['incident_error'] = "U heeft niet alle velden ingevuld.";

            else if(!in_array($_POST["component"], $component_ids))
                $this->data['incident_error'] = "Het gekozen component bestaat niet.";

            else {
                // Load the incident model and create a new incident
                $incident_model = $this->loadModel("IncidentModel");

                $incident_model->createIncident(
                    $_POST["component"],
                    $_POST["short_description"],
                    $_POST["description"],
                    $this->account['id']
                );

                $this->data['incident_success'] = true;
            }
        }
    }
}