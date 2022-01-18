<?php

function autoload_class($class_name)
{
    // Split the class name up if it contains backslashes.
    $classNameParts = explode('\\', $class_name);

    // The last piece of the array will be our class name.
    $className = end($classNameParts);

    $path_to_file = __DIR__ .'/../classes/' . $className . '.php';

    if (file_exists($path_to_file)) {
        require $path_to_file;
    }
}
