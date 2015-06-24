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
     * @param $args array URL params
     */
    public function incidentDetail($args) {
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

    /**
     * Deletes an incident and redirects back to the backend dashboard afterwards
     * @param $args array URL params
     */
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

    /**
     * Renders an overview of currently known questionnaires
     * @param $args array URL params
     */
    public function questionnaires($args) {
        // Load the questionnaires
        $qs_model = $this->loadModel("QuestionnaireModel");
        $this->data['questionnaires'] = $qs_model->allObjects();

        // Render the view
        $this->data['page'] = 'questionnaires';
        $this->renderView("backend/questionnaires");
    }

    /**
     * Renders a view to create a new questionnaire and handles posts of that form
     * @param array $args URL params
     */
    public function questionnairesCreate($args) {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check input data
            if(!isset($_POST["title"]) || !isset($_POST["icon"]) || empty($_POST["title"]) || empty($_POST["icon"]))
                $this->data['error'] = 'Vul alle velden in.';

            else {
                // Load models
                $qs_model = $this->loadModel("QuestionnaireModel");
                $qs_entry_model = $this->loadModel("QuestionnaireEntryModel");

                // Create the questionnaire
                $questionnaire = $qs_model->createQuestionnaire($_POST["title"], $_POST["icon"]);

                // Placeholder for back-updating
                $to_update = array('id' => null, 'yes' => false, 'no' => false);

                // Process every question as long as the following information is available for it:
                //  * The question itself
                //  * The yes-action
                //  * The no-action
                for(
                    $entry = 1;
                    (   isset($_POST["question_{$entry}_question"]) &&
                        isset($_POST["question_{$entry}_yes_action"]) &&
                        isset($_POST["question_{$entry}_no_action"])
                    );
                    $entry++
                ) {
                    // Fetch data
                    $question = $_POST["question_{$entry}_question"];
                    $actions = array(
                        'yes' => $_POST["question_{$entry}_yes_action"],
                        'no'  => $_POST["question_{$entry}_no_action"]
                    );
                    $workarounds = array(
                        'yes' => $_POST["question_{$entry}_yes_workaround"],
                        'no' => $_POST["question_{$entry}_no_workaround"]
                    );

                    // Create a new question entry
                    $entry_obj = $qs_entry_model->createQuestionnaireEntry(
                        $questionnaire['id'],
                        $question,
                        (($actions['yes'] == 'workaround') ? $workarounds['yes'] : null),
                        (($actions['no'] == 'workaround') ? $workarounds['no'] : null)
                    );

                    // Update old question if set and necessary
                    if($to_update['id'] !== null && ($to_update['yes'] || $to_update['no'])) {
                        $qs_entry_model->updateEntryNextQuestionIDs(
                            $to_update['id'],
                            ($to_update['yes']) ? $entry_obj['entry_id'] : null,
                            ($to_update['no']) ? $entry_obj['entry_id'] : null
                        );
                    }

                    // Update the back-updating array for the next question
                    $to_update = array(
                        'id' => $entry_obj['entry_id'],
                        'yes' => ($actions['yes'] == 'next_question'),
                        'no' => ($actions['no'] == 'next_question'),
                    );
                }

                // Questionnaire has been created. Redirect to the overview view.
                $this->redirectToUrl("/backend/questionnaires");
            }
        }

        // Render the view
        $this->data['page'] = 'questionnaires';
        $this->renderView("backend/create_questionnaire");
    }

    /**
     * Deletes an questionnaire and redirects back to the questionnaire overview view afterwards
     * @param $args array URL params
     */
    public function questionnairesDelete($args) {
        // Load models and the questionnaire itself
        $qs_model = $this->loadModel("QuestionnaireModel");
        $qse_model = $this->loadModel("QuestionnaireEntryModel");
        $questionnaire = $qs_model->getObjectByPk($args[1]);

        // Show 404 when no incident was found
        if($questionnaire === false)
            $this->MvcInstance->dieWithDebugMessageOr404(
                "Questionnaire not found",
                array("URL arguments" => $args)
            );

        // Delete questionnaire entries and the questionnaire itself
        $qse_model->deleteWithQuestionnaireId($args[1]);
        $qs_model->deleteWithPk($args[1]);

        // Redirect back to the dashboard
        $this->redirectToUrl("/backend/questionnaires");
    }

    /**
     * Renders the hardware overview page
     * @param $args
     */
    public function hardware($args) {
        // Load the model
        $hardware_model = $this->loadModel("HardwareModel");
        $this->data['hardwarelist'] = $hardware_model->allObjects();

        // Render the page
        $this->data['page'] = 'hardware';
        $this->renderView("backend/hardware");
    }

    /**
     * Renders the hardware detail page
     * @param $args
     */
    public function hardwareDetail($args) {
        // Load all models
        $hardware_model = $this->loadModel("HardwareModel");
        $software_model = $this->loadModel("SoftwareModel");

        // Load the CI item
        $this->data['ci'] = $hardware_model->getObjectByPk($args[1]);

        // Show 404 when no hardware was found
        if($this->data['ci'] === false)
            $this->MvcInstance->dieWithDebugMessageOr404(
                "Hardware not found",
                array("URL arguments" => $args)
            );

        // Get related software
        $this->data['software'] = $software_model->getSoftwareForHardware($args[1]);

        // Render the hardware detail view
        $this->data['page'] = 'hardware';
        $this->renderView("backend/detail_hardware");
    }
}