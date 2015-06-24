<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * development.php
 * P14-helpdesk_hondsrug
 *
 * Contains the application settings.
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       21/06/2015
 */

require_once "mvc/MvcApplication.php";

class development extends MvcApplication {
    /**
     * @var string Name of the application
     */
    public $appName = "P14-helpdesk_hondsrug";

    /**
     * @var string Version of the application
     */
    public $appVersion = "1.0.0";

    /**
     * @var bool Whether or not the enable detailed debug information
     */
    public $debug = true;

    /**
     * @var array Database connection configuration.
     *
     * Should at least contain:
     *      - Host
     *      - Username
     *      - Password
     *      - Database
     */
    protected $database = array(
        'host'      => 'localhost',
        'username'  => 'root',
        'password'  => '',
        'database'  => 'hanze_hondsrug'
    );

    /**
     * @var array Configured URLs for the application.
     *
     * Should be in a format as follows:
     *      '${Regex URL matcher}' => ['${Controller}', '${Function}']
     *
     * The controllername should match the filename and classname of the controller
     * you want to dispatch to. The functionname is called on the controller.
     */
    protected $urls = array(
        '{^(.*)$}'                           => array('DefaultController', 'render404'),

        '{^/?$}'                             => array('FrontendController', 'dashboard'),
        '{^/questionnaire/([0-9-]+)$}'       => array('FrontendController', 'questionnaire'),

        '{^/backend$}'                       => array('BackendController', 'dashboard'),
        '{^/backend/incident/([0-9-]+)$}'    => array('BackendController', 'incidentDetail'),
        '{^/backend/incident/([0-9-]+)/delete$}' => array('BackendController', 'incidentDelete'),
        '{^/backend/questionnaires$}'        => array('BackendController', 'questionnaires'),
        '{^/backend/questionnaires/create$}' => array('BackendController', 'questionnairesCreate'),
        '{^/backend/questionnaires/([0-9-]+)/delete$}' => array('BackendController', 'questionnairesDelete'),
        '{^/backend/hardware$}'              => array('BackendController', 'hardware'),
        '{^/backend/hardware/([[:alnum:]-]+)*$}' => array('BackendController', 'hardwareDetail'),

        '{^/login$}'                         => array('IdentityController', 'login'),
        '{^/logout$}'                        => array('IdentityController', 'logout'),
    );

    /**
     * @var array Array of middleware
     */
    public $middleware = array(
        'identity' => 'IdentityMiddleware'
    );
}