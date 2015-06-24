<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * QuestionnaireModel.php
 * P14-helpdesk_hondsrug
 *
 * Model for the questionnaire table
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       23/06/2015
 */
class QuestionnaireModel extends MvcBaseModel {
    /**
     * @var string Database table name for this model
     */
    protected $tableName = "vragenlijst";

    /**
     * @var string Database table primary key field name for this model
     */
    protected $tablePrimaryKeyField = "id";

    /**
     * Creates a new questionnaire and returns the resulting entry
     * @param string $title Title for the questionnaire
     * @param string $icon Icon for the questionnaire
     * @return array The resulting entry
     * @throws Exception
     */
    public function createQuestionnaire($title, $icon) {
        // Set up query
        $query = $this->MvcInstance->db_conn->prepare(
            "INSERT INTO {$this->tableName} SET" .
            " titel = :title," .
            " icoon = :icon," .
            " datum = NOW()"
        );

        // .. and execute it
        if($query->execute(array(
            ':title' => $title,
            ':icon' => $icon
        )))
            return $this->getObjectByPk($this->MvcInstance->db_conn->lastInsertId());
        else
            throw new Exception($query->errorInfo()[2]);
    }
}