<?php
require('autoloader.php');
if(!empty($_POST['controller'])){
    if(isset($_POST['view']))
        require($_POST['controller'].'/'.$_POST['view'].'.php');
    else{
        controller\Request::loadController();
    }
}else{
    $router = new controller\Router(new controller\Request);
    $router->params=array('2' => 'key','3' => 'orderid','4' => 'status','5' => 'message');
    $router->get('service/nummus.php', function($request){
        $nummus = new controller\nummus();
        $nummus->nummusInterface($request);
    }, 2);
    $router->get('/service/nummus', function($request){
        $nummus = new controller\nummus();
        $nummus->nummusAPI($request);
    });
    $router->get('/home', function($request){
        require('view/index.html');
    });
    $router->get('settings', function($request){
        require('view/index.html');
    });
    $router->post('/service/data', function($request){
        return json_encode($request->getBody());
    });
    $router->get('/service/dump', function($request){
        \model\data::pr($_POST);
        \model\data::pr($_GET);
    });
    $router->get('/service/ScanController.php', function($request){
        \model\data::pr($_POST);
        \model\data::pr($_GET);
    });
}
?>
