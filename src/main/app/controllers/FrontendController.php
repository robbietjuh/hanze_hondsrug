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

class FrontendController extends MvcBaseController {
    /**
     * @var IdentityMiddleware Shared instance of the IdentityMiddleware middleware
     */
    protected $im;

    /**
     * @var array Current user account
     */
    protected $account;

    /**
     * Populates the controller's base variables
     * @param $sender MvcApplication The MvcApplication that dispatched to the controller
     */
    public function __construct($sender) {
        parent::__construct($sender);

        // Set up the middleware
        $this->im = $this->MvcInstance->middleware['identity'];

        // Check whether the user account has access
        $this->im->checkRole(1);
        $this->account = $this->im->getAccount();

        // Set up user data
        $this->data['username'] = $this->account['gebruikersnaam'];
        $this->data['displayname'] = $this->account['weergavenaam'];
        $this->data['role'] = $this->account['rol'];
    }

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