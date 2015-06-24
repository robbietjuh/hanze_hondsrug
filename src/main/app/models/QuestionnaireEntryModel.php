<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * QuestionnaireEntryModel.php
 * P14-helpdesk_hondsrug
 *
 * Model for the questionnaire entries table
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       24/06/2015
 */
class QuestionnaireEntryModel extends MvcBaseModel {
    /**
     * @var string Database table name for this model
     */
    protected $tableName = "vragenlijst_entries";

    /**
     * @var string Database table primary key field name for this model
     */
    protected $tablePrimaryKeyField = "entry_id";

    /**
     * Creates a new questionnaire entry and returns the resulting entry
     * @param int $questionnaire_id Parent questionnaire ID
     * @param string $question Question to link to this entry
     * @param string|null $yes_workaround Workaround in case of 'Yes' answer
     * @param string|null $no_workaround Workaround in case of 'No' answer
     * @return array The resulting entry
     * @throws Exception
     */
    public function createQuestionnaireEntry($questionnaire_id, $question, $yes_workaround, $no_workaround) {
        // Set up query
        $query = $this->MvcInstance->db_conn->prepare(
            "INSERT INTO {$this->tableName} SET" .
            " vragenlijst_id = :qs_id," .
            " vraag = :question," .
            " ja_workaround = :yes_wk," .
            " nee_workaround = :no_wk"
        );

        // .. and execute it
        if($query->execute(array(
            ':qs_id' => $questionnaire_id,
            ':question' => $question,
            ':yes_wk' => $yes_workaround,
            ':no_wk' => $no_workaround
        )))
            return $this->getObjectByPk($this->MvcInstance->db_conn->lastInsertId());
        else
            throw new Exception($query->errorInfo()[2]);
    }

    /**
     * Updates entry question IDs for their yes and no clauses and returns the resulting entry
     * @param int $entry_id ID of the entry to update
     * @param int $yes_question ID of the question to show for the yes clause
     * @param int $no_question ID of the question to show for the no clause
     * @return array The resulting entry
     * @throws Exception
     */
    public function updateEntryNextQuestionIDs($entry_id, $yes_question, $no_question) {
        // Set up query
        $query = $this->MvcInstance->db_conn->prepare(
            "UPDATE {$this->tableName} SET" .
            " ja_vraag = :yes_qs," .
            " nee_vraag = :no_qs " .
            "WHERE" .
            " entry_id = :entry_id"
        );

        // .. and execute it
        if($query->execute(array(
            ':entry_id' => $entry_id,
            ':yes_qs' => $yes_question,
            ':no_qs' => $no_question
        )))
            return $this->getObjectByPk($this->MvcInstance->db_conn->lastInsertId());
        else
            throw new Exception($query->errorInfo()[2]);
    }

    public function getEntriesForQuestionnaire($questionnaire_id) {
        return $this->allObjectsWithQuery("WHERE vragenlijst_id = :id", array(':id' => $questionnaire_id));
    }
}