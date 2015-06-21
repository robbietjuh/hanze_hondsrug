<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MvcBaseModel.php
 * P14-helpdesk_hondsrug
 *
 * This file contains the base model class.
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       21/06/2015
 */

class MvcBaseModel {
    /**
     * @var string Database table name for this model
     */
    protected $tableName = "";

    /**
     * @var string Database table primary key field name for this model
     */
    protected $tablePrimaryKeyField = "";

    /**
     * @var MvcApplication A pointer to the main MvcApplication instance
     */
    protected $MvcInstance;

    /**
     * Populates the model's base variables and verifies the model
     * @param $sender MvcApplication Sending MVC application object
     */
    public function __construct($sender) {
        // Set base variables
        $this->MvcInstance = $sender;

        // Verify the model
        $this->verifyModel();
    }

    /**
     * Verifies the model
     */
    private function verifyModel() {
        // Check basic variables
        if(empty($this->tableName)) $error = "No table name was set";
        else if(empty($this->tablePrimaryKeyField)) $error = "No primary key field was set";

        // Stop on errors
        if(isset($error))
            $this->MvcInstance->dieWithDebugMessageOr404(
                "Invalid model",
                array("message" => $error)
            );
    }

    /**
     * Fetches all objects in the database for the given model and query
     * @param $query string SQL query to append to the SELECT query
     * @param $data array Data to use for the query
     * @return array An array of objects
     */
    public function allObjectsWithQuery($query="", $data=array()) {
        // Fetch all objects
        $query = $this->MvcInstance->db_conn->prepare("SELECT * FROM {$this->tableName} $query");
        if(!$query->execute($data))
            $this->MvcInstance->dieWithDebugMessageOr404(
                "Error while executing query",
                array(
                    'error' => $query->errorInfo(),
                    'query' => $query,
                    'data' => $data
                ));

        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        // Return an array of objects
        return $results;
    }

    /**
     * Fetches all objects in the database for the given model
     * @return array A result of objects
     */
    public function allObjects() {
        return $this->allObjectsWithQuery();
    }

    /**
     * Fetch the object with the specified primary key from the database
     * @param $pk int The primary key for the object to fetch
     * @return mixed The object or false if none were found
     */
    public function getObjectByPk($pk) {
        // Fetch the object
        $results = $this->allObjectsWithQuery("WHERE {$this->tablePrimaryKeyField} = :pk", array("pk" => $pk));
        if(count($results) == 0) return false;
        else return $results[0];
    }

    /**
     * Deletes an object from the database
     * @param $pk int Primary key value of the object to delete
     */
    public function deleteWithPk($pk) {
        // Delete the object
        $query = $this->MvcInstance->db_conn->prepare(
            "DELETE FROM {$this->tableName} WHERE {$this->tablePrimaryKeyField} = :pk"
        );

        if(!$query->execute(array(":pk" => $pk)))
            $this->MvcInstance->dieWithDebugMessageOr404(
                "Error while executing query",
                array('error' => $query->errorInfo())
            );
    }

    /**
     * Loads a model
     * @param $model string Name of the model to load
     * @return MvcBaseModel The model
     */
    public function loadModel($model) {
        // Check whether the model exists
        if(!file_exists("app/models/$model.php"))
            $this->MvcInstance->dieWithDebugMessageOr404(
                "Could not load the model specified: file not found",
                array('model' => $model));

        require_once "app/models/$model.php";

        $model = new $model($this->MvcInstance);
        return $model;
    }
}
