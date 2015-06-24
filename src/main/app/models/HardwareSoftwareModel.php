<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * HardwareSoftwareModel.php
 * P14-helpdesk_hondsrug
 *
 * Model for the hardware-software relationship table
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       24/06/2015
 */
class HardwareSoftwareModel extends MvcBaseModel {
    /**
     * @var string Database table name for this model
     */
    protected $tableName = "hardware_software";

    /**
     * @var string Database table primary key field name for this model
     */
    protected $tablePrimaryKeyField = "NULL";

    /**
     * Returns all relation objects for the given hardware ID
     * @param string $hardware_id Hardware ID
     * @return array
     */
    public function getSoftwareForHardware($hardware_id) {
        return $this->allObjectsWithQuery("WHERE hardware_identificatiecode = :id", array(':id' => $hardware_id));
    }

    /**
     * Sets up a new relationship between given hardware ID and given software ID
     * @param string $hardware_id Hardware ID
     * @param string $software_id Software ID
     * @return array
     * @throws Exception
     */
    public function createRelationship($hardware_id, $software_id) {
        // Set up query
        $query = $this->MvcInstance->db_conn->prepare(
            "INSERT INTO {$this->tableName} SET" .
            " hardware_identificatiecode = :hardware_id," .
            " software_identificatiecode = :software_id"
        );

        // .. and execute it
        if($query->execute(array(
            ':hardware_id' => $hardware_id,
            ':software_id' => $software_id
        )))
            return $this->getObjectByPk($this->MvcInstance->db_conn->lastInsertId());
        else
            throw new Exception($query->errorInfo()[2]);
    }
}