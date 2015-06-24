<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * SoftwareModel.php
 * P14-helpdesk_hondsrug
 *
 * Model for the software table
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       24/06/2015
 */
class SoftwareModel extends MvcBaseModel {
    /**
     * @var string Database table name for this model
     */
    protected $tableName = "software";

    /**
     * @var string Database table primary key field name for this model
     */
    protected $tablePrimaryKeyField = "identificatiecode";

    /**
     * Gets all software objects for the given hardware relation
     * @param string $hardware_id Hardware ID
     * @return array
     */
    public function getSoftwareForHardware($hardware_id) {
        // Load the model
        $relation_table = $this->loadModel("HardwareSoftwareModel");
        $results = array();

        // Fetch all related software objects
        foreach($relation_table->getSoftwareForHardware($hardware_id) as $entry)
            $results[] = $this->getObjectByPk($entry['software_identificatiecode']);

        // Return the results
        return $results;
    }
}