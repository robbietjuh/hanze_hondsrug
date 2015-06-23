<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * UserModel.php
 * P14-helpdesk_hondsrug
 *
 * Model for the user table
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       23/06/2015
 */
class UserModel extends MvcBaseModel {
    /**
     * @var string Database table name for this model
     */
    protected $tableName = "gebruiker";

    /**
     * @var string Database table primary key field name for this model
     */
    protected $tablePrimaryKeyField = "id";

    /**
     * Fetches all registered accounts with the given username
     * @param $username string Username
     * @return mixed Account entry or false
     */
    public function getObjectByUsername($username) {
        $results = $this->allObjectsWithQuery(
            "WHERE gebruikersnaam = :username",
            array(':username' => $username)
        );

        return (count($results) == 1) ? $results[0] : false;
    }

    /**
     * Returns users that match the given username and password combination.
     * Should normally only return an empty array or exactly one (1) user entry.
     * @param $username string Username
     * @param $password string Password
     * @return array
     */
    public function checkCredentials($username, $password) {
        $user_object = $this->getObjectByUsername($username);

        if($user_object === false) return array();
        $salt = substr($user_object['wachtwoord'], 0, 32);

        return ($user_object['wachtwoord'] == $this->hashPassword($password, $salt)) ? array($user_object) : array();
    }

    /**
     * Securely hashes a password with a salt
     * @param string $password The password to hash
     * @param string $salt An optional known salt
     * @return string
     */
    private function hashPassword($password, $salt=null){
        if($salt == null) $salt = md5(rand());

        $outcome = $salt . hash('sha256', $salt . $password);

        return $outcome;
    }
}