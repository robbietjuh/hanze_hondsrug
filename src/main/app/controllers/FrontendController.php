<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * FrontendController.php
 * P14-helpdesk_hondsrug
 *
 * Frontend controller
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       23/06/2015
 */

class FrontendController extends MvcBaseController {
    /**
     * @var IdentityMiddleware Shared instance of the IdentityMiddleware middleware
     */
    protected $im;

    /**
     * @var array Current user account
     */
    protected $account;

    /**
     * Populates the controller's base variables
     * @param $sender MvcApplication The MvcApplication that dispatched to the controller
     */
    public function __construct($sender) {
        parent::__construct($sender);

        // Set up the middleware
        $this->im = $this->MvcInstance->middleware['identity'];

        // Check whether the user account has access
        $this->im->checkRole(1);
        $this->account = $this->im->getAccount();
    }

    public function dashboard($args) {
        echo "Ingelogd als ".$this->account['gebruikersnaam'];
    }
}