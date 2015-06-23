<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * IdentityController.php
 * P14-helpdesk_hondsrug
 *
 * Identity controller
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       23/06/2015
 */

class IdentityController extends MvcBaseController {
    /**
     * @var IdentityMiddleware Shared instance of the IdentityMiddleware middleware
     */
    protected $im;

    /**
     * Populates the controller's base variables
     * @param $sender MvcApplication The MvcApplication that dispatched to the controller
     */
    public function __construct($sender) {
        parent::__construct($sender);

        // Set up the middleware
        $this->im = $this->MvcInstance->middleware['identity'];
    }

    /**
     * Renders and handles login requests.
     *
     * This function will render a login view when the user is not
     * currently logged in and has not submitted a login request.
     *
     * This function will try to authenticate the user against the
     * IdentityMiddleware middleware when a login request is submitted.
     * If the authentication succeeds, the IdentityMiddle middleware
     * will redirect the user to another page. If the authentication
     * does not succeed, the login view will be rendered with the
     * received error message.
     *
     * This function will redirect the user to the homepage when the
     * user is already logged in. This prevents the user from viewing
     * the login page in case the user is already authenticated.
     *
     * @see IdentityMiddleware
     * @param $args array URL parameters
     */
    public function login($args) {
        try {
            // Redirect to the homepage if the user is already
            // logged in at the moment.
            $this->im->getUserId();
            $this->redirectToUrl("/");
        }
        catch(Exception $ignored) {}

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                // Process login request
                $user = $_POST["username"];
                $password = $_POST["password"];
                $this->im->authenticateUser($user, $password);
            }
            catch(LoginException $e) {
                // User could not be logged in
                $this->data['error'] = "Ongeldige gebruikersnaam en wachtwoord.";
            }
        }

        $this->renderView("identity/login");
    }

    /**
     * Requests the Identity Middleware to log the user out.
     * @param $args array URL parameters
     */
    public function logout($args) {
        // Require the user to be logged in
        $this->im->checkRole(1, '/');

        // Log out the user
        $this->im->logout('/');
    }
}