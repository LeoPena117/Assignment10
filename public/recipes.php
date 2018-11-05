<?php
require '../vendor/autoload.php';
require '../generated-conf/config.php';

$settings = ['displayErrorDetails' => true];

$app = new \Slim\App(['settings' => $settings]);

$container = $app->getContainer();
$container['view'] = function($container) {
    $view = new \Slim\Views\Twig('../templates');
    
    $basePath = rtrim(str_ireplace('index.php', '', 
    $container->get('request')->getUri()->getBasePath()), '/');

    $view->addExtension(
    new Slim\Views\TwigExtension($container->get('router'), $basePath));
    
    return $view;
};


$app->get('/{Id}', function($request, $response, $args) {

    // access named argument from path
    $Q = RecipeQuery::create()->findPk($args['Id']);
    $name = $Q->getName();
    $desc = $Q->getDescription();
    $pTime = $Q->getPrepTime()."min";
    $tTime = $Q->getTotalTime()."min";
    $imgUrl = $Q->getImageUrl();
    $SQ = StepsQuery::create()->findByRecipeId($Q->getId());
    $steps = array();
    for($i=0;$i<count($SQ);$i++){
    	$steps[$i] = $SQ[$i]->getDescription();
    }


    $hold=array("Name"=>$name, "Desc"=>$desc, "pTime" => $pTime, "tTime" => $tTime,
     "imgUrl" => $imgUrl, "steps" => $steps, "ID"=>$args['Id']);


    // template rendering, passing data (name variable)
    return $this->view->render($response, "SingleRecipe.html", array('hold' => $hold));

});

$app->get('/', function ($request, $response, $args) {
    $Everything = RecipeQuery::create()->find();
    $this->view->render($response, 'MyRecipes.html', ["Query" => $Everything]);

    return $response;
})->setName('home');


$app->get('/sort/Name', function($request, $response, $args) {
	$Everything = RecipeQuery::create()->orderByName();

    return $this->view->render($response, "MyRecipes.html", ['Query' => $Everything]);
});

$app->get('/handlers/edit_recipe/{ID}/{Name}',
    function($request,$response,$args){
        
        $Recipe = RecipeQuery::create()->findOneById($args['ID']);
        $Recipe->setName($args['Name']);
        $Recipe->save();
    });

$app->get('/handlers/edit_steps/{RID}/{SID}/{Step}',
    function($request,$response,$args){
        $Steps = StepsQuery::create()->findByRecipeId($args['RID']);
        $Steps[$args['SID']-1]->setDescription($args['Step']);
        $Steps->save();
    });

$app->get('/handlers/swap_steps/{RID}/{SID1}/{SID2}',
    function($request,$response,$args){
        $Recipe = StepsQuery::create()->findByRecipeId($args['RID']);
        $Hold = $Recipe[$args['SID1']-1]->getDescription();
        $Recipe[$args['SID1']-1]->setDescription($Recipe[$args['SID2']-1]->getDescription());
        $Recipe[$args['SID2']-1]->setDescription($Hold);
        $Recipe->save();
    });

$app->get('/handlers/add_step/{RID}/{SID}/{Step}',
    function($request,$response,$args){
        $Step = new Steps();
        $Step->setStepNumber($args['SID']);
        $Step->setDescription($args['Step']);
        $Step->setRecipeId($args['RID']);
        echo $Step;
        $Step->save();
    });


$app->run();



?>