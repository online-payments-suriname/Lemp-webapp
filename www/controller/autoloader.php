<?php
session_start();
    // {path}/autoloader.php
function getIncludePath($i) {

    $includePaths=explode(PATH_SEPARATOR, get_include_path());
    return $includePaths[$i];
}

function loadClass($className){
    $fileName = '';
    $namespace = '';
    // Sets the include path as the "src" directory
    $includePath = getIncludePath(1).DIRECTORY_SEPARATOR;
    if (false !== ($lastNsPos = strripos($className, '\\'))) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= $className . '.php';
    $fullFileName = $includePath . $fileName;

    if (file_exists($fullFileName)) {
        require $fullFileName;
    } else {
        echo 'Class "'.$className.'" does not exist.';
    }
}
    spl_autoload_register('loadClass'); // Registers the autoloader
?>
