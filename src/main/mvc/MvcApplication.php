<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MvcApplication.php
 * P14-helpdesk_hondsrug
 *
 * This file contains the main class for the MVC framework, including the bootstrapper,
 * database connectors and url dispatcher.
 *
 * @author     R. de Vries <r.devries@robbytu.net>
 * @version    1.0.0
 * @date       21/06/2015
 */

session_start();

require_once "mvc/MvcBaseController.php";
require_once "mvc/MvcBaseModel.php";

class MvcApplication {
    /**
     * @var string Name of the application
     */
    public $appName;

    /**
     * @var string Version of the application
     */
    public $appVersion;

    /**
     * @var bool Whether or not the enable detailed debug information
     */
    public $debug = false;

    /**
     * @var array Database connection configuration.
     *
     * Should at least contain:
     *      - Host
     *      - Username
     *      - Password
     *      - Database
     */
    protected $database;

    /**
     * @var array Configured URLs for the application.
     *
     * Should be in a format as follows:
     *      '${Regex URL matcher}' => ['${Controller}', '${Function}']
     *
     * The controllername should match the filename and classname of the controller
     * you want to dispatch to. The functionname is called on the controller.
     */
    protected $urls;

    /**
     * @var mixed Currently used controller object
     */
    protected $controller;

    /**
     * @var array Array of middleware
     */
    public $middleware = array();

    /**
     * @var bool
     */
    private $errorState = false;

    /**
     * Bootstrap the MVC framework.
     */
    public function __construct() {
        // Prevents javascript XSS attacks aimed to steal the session ID
        ini_set('session.cookie_httponly', 1);

        // Session ID cannot be passed through URLs
        ini_set('session.use_only_cookies', 1);

        // Uses a secure connection (HTTPS) if possible
        ini_set('session.cookie_secure', 1);

        try {
            // Set up a database connection
            $this->db_conn = new PDO(
                "mysql:host={$this->database['host']};dbname={$this->database['database']}",
                $this->database['username'],
                $this->database['password']);
        }
        catch(Exception $error) {
            // Report the failure to the user
            $this->dieWithDebugMessageOr404(
                'Could not connect to the database server',
                array('message' => $error->getMessage()));
        }

        // Protect sensitive settings
        $this->database = 'PROTECTED';

        // Load middleware classes
        $this->initMiddleware();

        // Set up sessions
        $this->initSessions();

        // Dispatch the url to the appropriate controller
        $this->dispatch();
    }

    /**
     * Initializes the session backend and prevents PHPSESSID hijacking.
     */
    private function initSessions() {
        if(!isset($_SESSION["ip"])) {
            $_SESSION["ip"] = $_SERVER['REMOTE_ADDR'];
        }

        if($_SESSION["ip"] != $_SERVER["REMOTE_ADDR"]) {
            session_regenerate_id();
            session_destroy();
            $_SESSION = array();
            $_SESSION["ip"] = $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Parses the URL and compares it to the urldefs in the application's config. Dispatches
     * to the controller if a match is found.
     */
    public function dispatch() {
        // Loop through the configured urls and check whether they match
        // with any of the configured urls in the application config.
        foreach($this->urls as $pattern => $callback) {
            if (preg_match($pattern, $_SERVER['REQUEST_URI'], $matches) !== false) {
                if(count($matches) > 0) {
                    $controller = $callback[0];
                    $function = $callback[1];
                    $args_by_url = $matches;
                }
            }
        }

        // Check whether a match was found
        if(!isset($controller) || !isset($function) || !isset($args_by_url))
            $this->dieWithDebugMessageOr404(
                "No urldef match found for requested url",
                array("url" => $_SERVER['REQUEST_URI'], "urldefs" => $this->urls));

        $this->dispatchController($controller, $function, $args_by_url);
    }

    /**
     * Dispatches to the specified controller
     * @param $controller string
     * @param $function string
     * @param $args array
     */
    protected function dispatchController($controller, $function, $args) {
        // Check wether the controller exists
        if(!file_exists("app/controllers/$controller.php"))
            $this->dieWithDebugMessageOr404(
                "Could not dispatch to controller: file not found",
                array('url' => $_SERVER['REQUEST_URI'], 'controller' => $controller));

        // Include the controller file
        require_once "app/controllers/$controller.php";

        // Initialize the controller class
        $this->controller = new $controller($this);

        // Call the method specified in the URL definition
        $this->controller->$function($args);
    }

    /**
     * Initializes all middleware as specified in $middleware
     */
    protected function initMiddleware() {
        $loadables = $this->middleware;
        foreach($loadables as $name => $middleware) {
            // Check wether the middleware exists
            if(!file_exists("app/middleware/$middleware.php"))
                $this->dieWithDebugMessageOr404(
                    "Could not load middleware class: " . htmlentities($middleware),
                    array('middleware' => $middleware));

            // Include the middleware file
            require_once "app/middleware/$middleware.php";

            // Initialize the middleware class
            $this->middleware[$name] = new $middleware($this);
        }
    }

    /**
     * Prints an error message to the screen if @see $debug is enabled,
     * otherwise prints a '404 not found' message and exits.
     *
     * @param string $title The title to be shown in the error message
     * @param array $details Detailed information to be displayed
     */
    public function dieWithDebugMessageOr404($title, $details=array()) {
        // Set the HTTP response code
        header("HTTP/1.1 404 Not Found");

        if(!$this->debug && !$this->errorState) {
            $this->dispatchController("DefaultController", "render404", array());
        }

        // HTML head
        if($this->debug || $this->errorState) {
            echo "<!doctype html>";
            echo "<html>";
            echo "<head>";
            echo "<style type='text/css'>body { font-family: Arial; }</style>";
            echo "<title>{$this->appName} {$this->appVersion}</title></head>";
            echo "<body>";
        }

        // Check whether to display detailed debug information or not
        if(!$this->debug && $this->errorState) {
            echo "<h1>404 Not Found</h1>";
            echo "The requested page could not be found.";
            echo "<hr>";
            echo "<i>{$this->appName} {$this->appVersion}</i>";
        }
        else if($this->debug && !$this->errorState) {
            echo "<h1>$title</h1>";
            echo "A detailed description will follow. Turn off <code>debug</code> in production!";

            // You may want to add additional variables to be printed to the screen
            $toPrint = array('Debug information' => $details,
                             'GET global' => $_GET,
                             'POST global' => $_POST,
                             'SERVER global' => $_SERVER,
                             'SESSION global' => $_SESSION);

            foreach($toPrint as $description => $data) {
                // Skip empty arrays
                if(is_array($data) && count($data) == 0) continue;

                // Print information to the screen
                echo "<hr />";
                echo "<strong>$description</strong>";
                echo "<pre>";
                print_r($data);
                echo "</pre>";
            }
        }

        // Footer
        if($this->debug || $this->errorState) {
            echo "<hr>";
            echo "<span style='font-style: italic;'>{$this->appName} {$this->appVersion}</span>";
            echo "</body>";
            echo "</html>";
        }

        // Terminate the application
        exit;
    }
}
