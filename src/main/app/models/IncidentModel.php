<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * IncidentModel.php
 * P14-helpdesk_hondsrug
 *
 * Model for the user table
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       23/06/2015
 */
class IncidentModel extends MvcBaseModel {
    /**
     * @var string Database table name for this model
     */
    protected $tableName = "incident";

    /**
     * @var string Database table primary key field name for this model
     */
    protected $tablePrimaryKeyField = "identificatiecode";

    /**
     * Creates a new incident in the database and returns the entry itself. If creation
     * fails, an exception will be thrown.
     *
     * @param int $hardware_component Hardware component entry ID
     * @param string $title Title for the incident
     * @param string $description Description for the incident
     * @param int $reporter User ID of the reporter
     * @return array
     * @throws Exception
     */
    public function createIncident($hardware_component, $title, $description, $reporter) {
        // Set up query
        $query = $this->MvcInstance->db_conn->prepare(
            "INSERT INTO {$this->tableName} SET" .
            " hardware_component = :hardware_component," .
            " datum = NOW()," .
            " titel = :title," .
            " omschrijving = :description," .
            " melder = :reporter"
        );

        // .. and execute it
        if($query->execute(array(
            ':hardware_component' => $hardware_component,
            ':title' => $title,
            ':description' => $description,
            ':reporter' => $reporter
        )))
            return $this->getObjectByPk($this->MvcInstance->db_conn->lastInsertId());
        else
            throw new Exception($query->errorInfo()[2]);
    }

    /**
     * Fetches all incident objects and resolves reporter user details with them
     * @return array
     */
    public function allObjects() {
        // Fetch all incidents
        $incidents = $this->allObjectsWithQuery("ORDER BY datum DESC");

        // Fetch user details for each incident
        $user_model = $this->loadModel("UserModel");
        foreach($incidents as &$entry) {
            $user = $user_model->getObjectByPk($entry['melder']);
            if($user !== false) $entry['melder'] = $user;
        }

        // Return the incidents
        return $incidents;
    }

    /**
     * Fetches an incident and resolves reporter user details for the given primary key
     * @param int $pk Primary key of the incident to search for
     * @return mixed False if nothing was found, otherwise an array
     */
    public function getObjectByPk($pk) {
        // Get the incident
        $incident = parent::getObjectByPk($pk);

        // Fetch user details if an incident was found
        if($incident !== false) {
            $user_model = $this->loadModel("UserModel");
            $user = $user_model->getObjectByPk($incident['melder']);
            if($user !== false) $incident['melder'] = $user;
        }

        // Return the incident
        return $incident;
    }
}