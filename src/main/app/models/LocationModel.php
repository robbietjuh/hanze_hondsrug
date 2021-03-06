<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * LocationModel.php
 * P14-helpdesk_hondsrug
 *
 * Model for the locations table
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       24/06/2015
 */
class LocationModel extends MvcBaseModel {
    /**
     * @var string Database table name for this model
     */
    protected $tableName = "locatie";

    /**
     * @var string Database table primary key field name for this model
     */
    protected $tablePrimaryKeyField = "locatie";
}