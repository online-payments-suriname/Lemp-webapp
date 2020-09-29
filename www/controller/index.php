<?php
require('autoloader.php');
if(!empty($_POST['controller']))
    require($_POST['controller'].'.php');
else{
$router = new controller\Router(new controller\Request);
//localhost/service/test/params/send/after/test
$router->params=array('2' => 'params','3' => 'send','4' => 'after','5' => 'test');
$router->get('/service/test', function($request){
    return 'test';
    controller\Router::redirect($request->requestScheme.'://'.$request->httpHost);
});
$router->post('/service/data', function($request){
    \model\data::pr($POST);
});
}
?>
