<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * IdentityMiddleware.php
 * P14-helpdesk_hondsrug
 *
 * Middleware that handles session authentication
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       21/06/2015
 */

class NotLoggedInException extends Exception {}
class InsufficientPermissionsException extends Exception {}
class LoginException extends Exception {}

class IdentityMiddleware extends MvcBaseController {
    /**
     * @var UserModel The User model
     */
    private $userModel;

    /**
     * Constructs a new instance of the IdentityMiddleware middleware
     * @param MvcApplication $sender The parent MvcApplication instance
     */
    public function __construct($sender) {
        parent::__construct($sender);

        $this->userModel = $this->loadModel("UserModel");
    }

    /**
     * Retrieves the current user's user ID
     * @return int The user ID
     * @throws NotLoggedInException Thrown when the user is not logged in currently
     */
    public function getUserId() {
        // Throw an exception when there is no UID set
        if(!isset($_SESSION["uid"]))
            throw new NotLoggedInException();

        return $_SESSION["uid"];
    }

    /**
     * Sets the current User ID and password
     * @param $id int User ID
     * @param $password string Password. Will be hashed.
     */
    private function setUserId($id, $password) {
        $_SESSION["uid"] = $id;
        if($password != null) $_SESSION["session_pw"] = hash('sha256', $password);
    }

    /**
     * Retrieves the current user account's details
     * @return mixed Account detail
     * @throws NotLoggedInException Thrown when the user is not logged in currently
     */
    public function getAccount() {
        // Request the user account object from the account model
        $account = $this->userModel->getObjectByPk($this->getUserId());

        // Check whether the user account was found
        if($account === false) {
            unset($_SESSION["uid"]);
            $this->redirectLogin($_SERVER["REQUEST_URI"]);
        }

        // Log out when the session password and the password of the account do not match anymore.
        else if(isset($_SESSION["session_pw"]) && hash('sha256', $account['wachtwoord']) != $_SESSION["session_pw"]) {
            unset($_SESSION["uid"]);
            $this->redirectLogin($_SERVER["REQUEST_URI"]);
        }

        // Set session_pw if not yet set
        else if(!isset($_SESSION["session_pw"])) {
            $this->setUserId($account['id'], $account['wachtwoord']);
        }

        return $account;
    }

    /**
     * Checks wether or not a user has a specified role assigned
     * to his account. If the user is not logged in currently, this will
     * redirect the user to the login screen. If the user is logged in
     * but does not have sufficient permissions, an error page will be
     * displayed.
     *
     * If you do not supply a @see $url for the login controller to do
     * a callback on, the current requested uri will be used to supply one.
     *
     * @param $role int The minimum role to check for
     * @param $url string Optional. The URL to redirect to as a callback
     */
    public function checkRole($role, $url = '') {
        try {
            // Try fetching the currently logged in user and compare its role
            // against the required role.
            $account = $this->getAccount();
            if($account['rol'] < $role)
                throw new InsufficientPermissionsException();
        }
        catch (NotLoggedInException $e) {
            // Redirect to the login page as the current session is not
            // authenticated.
            if($url == '') $url = $_SERVER['REQUEST_URI'];
            $this->redirectLogin($url);
        }
        catch (InsufficientPermissionsException $e) {
            // The user has insufficient permissions. Render an error page.
            $this->MvcInstance->dieWithDebugMessageOr404(
                "Insufficient permissions"
            );
        }
    }

    /**
     * Redirects the user to the login page.
     * @param $url string The callback URL to use upon succesful log on
     */
    public function redirectLogin($url) {
        $_SESSION["login_callback"] = $url;
        $this->redirectToUrl("/login");
    }

    /**
     * Authenticates the user against the user model. Upon success,
     * this will store the UID in the current session and redirect the
     * user to the homepage or the login callback URL (if set).
     * Otherwise throws a LoginException.
     *
     * @param $username string Username
     * @param $password string Password, unencrypted
     * @throws LoginException Thrown upon unsuccessful authentication
     */
    public function authenticateUser($username, $password) {
        // Search for a matching user account
        $matches = $this->userModel->checkCredentials($username, $password);

        // Check if there were any matches
        if(count($matches) == 1) {
            if(isset($_SESSION["login_callback"])) {
                $url = $_SESSION["login_callback"];
                unset($_SESSION["login_callback"]);
            }
            else
                $url = "/";

            $this->setUserId($matches[0]['id'], $matches[0]['wachtwoord']);
            $this->redirectToUrl($url);
            exit;
        }

        // No matches found
        throw new LoginException();
    }

    /**
     * Redirects logged in users to the given URL
     * @param $url string URL to redirect to
     */
    public function redirectSignedIn($url) {
        try {
            $this->getUserId();
            $this->redirectToUrl($url);
        }
        catch(Exception $ignored) {}
    }

    /**
     * Unsets the session user id and redirects the user to the specified URL.
     * @param string $url The URL to redirect the user to
     */
    public function logout($url='/') {
        unset($_SESSION['uid']);
        unset($_SESSION["session_pw"]);

        if($url !== false) {
            header("Location: $url");
            exit;
        }
    }
}
