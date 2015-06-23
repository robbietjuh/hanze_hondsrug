<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * HardwareComponentModel.php
 * P14-helpdesk_hondsrug
 *
 * Model for the user table
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       23/06/2015
 */
class HardwareComponentModel extends MvcBaseModel {
    /**
     * @var string Database table name for this model
     */
    protected $tableName = "hardware";

    /**
     * @var string Database table primary key field name for this model
     */
    protected $tablePrimaryKeyField = "identificatiecode";
}