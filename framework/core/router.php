<?php

    Class Router
    {
        const CONTROLLER_SUFFIX = "Controller";
        const METHOD_PREFIX = "action_";

        /*
            Description: Initiates Router calss object
            Parameters: null
            Return: (object Router) Initiated Router object
        */
        function init()
        {
            $router = new Router();
            return $router;
        }

        /*
            Description: Gathers information to call correct controller and method and calls createContent
            Parameters: null
            Return: null
        */
        function findContent()
        {
            $uriParameters = $this->getParameters();
            $controller = $uriParameters[1];
            $method = $uriParameters[2];

            include($GLOBALS["applicationPath"] . "/controllers/" . $controller . "Controller.php");

            $controllerName = $controller . self::CONTROLLER_SUFFIX;
            $methodName = self::METHOD_PREFIX . $method;
            $this->createContent($controllerName, $methodName);
        }

        /*
            Description: Process users called uri and finds appropriate controller and methdos name for findContend
            Parameters: null
            Return: (Array) parameters for findContent
        */
        function getParameters()
        {
            $applicationConfig = $GLOBALS["app"]->applicationConfig;
            $requestedUri = $_SERVER["REQUEST_URI"];
            $parameters = split("/", $requestedUri);

            if (substr($parameters[1], 0, 1) === '?')
            {
                $getString = str_replace('?', '', $parameters[1]);
            }

            if (isset($getString))
            {
                $parameters[1] = $applicationConfig["router"]["defaultController"];
                $parameters[2] = $applicationConfig["router"]["defaultMethod"];
            }
            else
            {
                if (!isset($parameters[1])) $parameters[1] = $applicationConfig["router"]["defaultController"];
                if (!isset($parameters[2])) $parameters[2] = $applicationConfig["router"]["defaultMethod"];

                if (empty($parameters[1])) $parameters[1] = $applicationConfig["router"]["defaultController"];
                if (empty($parameters[2])) $parameters[2] = $applicationConfig["router"]["defaultMethod"];
            }

            return $parameters;
        }

        /*
            Description: Calls correct controller and its method
            Parameters: required (String) $controllerName - Controller name which will be called
                        required (String) $methodName - Method name for controller which will be called
            Return: null
        */
        function createContent($controllerName, $methodName)
        {
            $controller = new $controllerName();
            $controller->{$methodName}();
        }
    }

?>