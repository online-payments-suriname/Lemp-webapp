<?php
session_start();
// {path}/autoloader.php
function getIncludePath($i) {

    $includePaths=explode(PATH_SEPARATOR, get_include_path());
    return $includePaths[$i];
}

function loadFile($className, $fileName, $i){
    // Sets the include path as the "src" directory
    $includePath = getIncludePath($i).DIRECTORY_SEPARATOR;
    $fullFileName = $includePath . $fileName;

    if (file_exists($fullFileName)) {
        require $fullFileName;
    } elseif($i<1){
          loadFile($className, $fileName, 1);
    }else{
        echo 'Class "'.$className.'" does not exist.';
    }
}

function loadClass($className){
    $fileName = '';
    $namespace = '';
    if (false !== ($lastNsPos = strripos($className, '\\'))) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= $className . '.php';
    loadFile($className, $fileName, 0);
}
spl_autoload_register('loadClass'); // Registers the autoloader
?>
