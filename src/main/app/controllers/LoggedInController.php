<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * LoggedInController.php
 * P14-helpdesk_hondsrug
 *
 * Base controller that handles user account data and minimum role detection
 * for controllers. Intended to be extended.
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       23/06/2015
 */

class LoggedInController extends MvcBaseController {

    /**
     * @var IdentityMiddleware Shared instance of the IdentityMiddleware middleware
     */
    protected $im;

    /**
     * @var array Current user account
     */
    protected $account;

    /**
     * @var int Minimum required role to view pages in this controller
     */
    protected $requiredRole = 1;

    /**
     * Populates the controller's base variables
     * @param $sender MvcApplication The MvcApplication that dispatched to the controller
     */
    public function __construct($sender) {
        parent::__construct($sender);

        // Set up the middleware
        $this->im = $this->MvcInstance->middleware['identity'];

        // Check whether the user account has access
        $this->im->checkRole($this->requiredRole);
        $this->account = $this->im->getAccount();

        // Set up user data
        $this->data['username'] = $this->account['gebruikersnaam'];
        $this->data['displayname'] = $this->account['weergavenaam'];
        $this->data['role'] = $this->account['rol'];
    }

}