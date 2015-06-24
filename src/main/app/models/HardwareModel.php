<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * HardwareModel.php
 * P14-helpdesk_hondsrug
 *
 * Model for the hardware table
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       24/06/2015
 */
class HardwareModel extends MvcBaseModel {
    /**
     * @var string Database table name for this model
     */
    protected $tableName = "hardware";

    /**
     * @var string Database table primary key field name for this model
     */
    protected $tablePrimaryKeyField = "identificatiecode";

    /**
     * Creates a new hardware entry and returns the resulting data
     * @param string $id
     * @param string $kind
     * @param string $brand
     * @param string $supplier
     * @param string $location
     * @return array
     * @throws Exception
     */
    public function createHardwareEntry($id, $kind, $brand, $supplier, $location) {
        // Set up query
        $query = $this->MvcInstance->db_conn->prepare(
            "INSERT INTO {$this->tableName} SET" .
            " identificatiecode = :id," .
            " soort = :kind," .
            " leverancier = :supplier," .
            " locatie = :location," .
            " merk = :brand, " .
            " aanschaf_jaar = :acq_year"
        );

        // .. and execute it
        if($query->execute(array(
            ':id' => $id,
            ':kind' => $kind,
            ':supplier' => $supplier,
            ':location' => $location,
            ':brand' => $brand,
            ':acq_year' => date('Y')
        )))
            return $this->getObjectByPk($id);
        else
            throw new Exception($query->errorInfo()[2]);
    }
}