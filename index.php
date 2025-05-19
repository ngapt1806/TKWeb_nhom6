<?php
    require './controllers/BaseController.php';
    require './core/Database.php';
    require './models/BaseModel.php';
    require './configs/uriConfig.php';

    $controllerName = ucfirst(strtolower($_REQUEST['controller'] ?? 'Home')) . 'Controller';
    $actionName = strtolower($_REQUEST['action'] ?? 'home');
    $controllerFile = "./controllers/$controllerName.php";

    if (file_exists($controllerFile)) {
        require $controllerFile;
        if (class_exists($controllerName)) {
            $controllerObj = new $controllerName();
            if (method_exists($controllerObj, $actionName)) {
                $controllerObj->$actionName();
            } else {
                echo "Action '$actionName' not found in controller '$controllerName'.";
            }
        } else {
            echo "Controller class '$controllerName' not found.";
        }
    } else {
        echo "Controller file '$controllerFile' not found.";
    }