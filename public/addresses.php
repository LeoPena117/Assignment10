<?php
require '../vendor/autoload.php';
require '../generated-conf/config.php';

//////////////////////
// Slim Setup
//////////////////////

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

//////////////////////
// Routes
//////////////////////

$app->get('/', function ($request, $response, $args) {
	$people = PersonQuery::create()->find();
	$this->view->render($response, 'people.html', [
		"people" => $people
		]);

	return $response;
})->setName('home');

$app->get('/person/{id}', function($request, $response, $args) {

	$person = PersonQuery::create()->findPk($args['id']);
	
	$this->view->render($response, 'person.html', [
		"person" => $person
		]);

	return $response;
})->setName('details');

//////////////////////
// AJAX handlers
//////////////////////

// phone number editor - by personid+seq or by pnid
$app->get('/handlers/edit_phone_number/{pnid}/{number}', 
	function($request, $response, $args) {
		// check that they are authorzied to edit

		$pn = PhoneNumberQuery::create()->findPk($args['pnid']);
		$pn->setNumber($args['number']);
		$pn->save();

		//$response->getBody()->write("OK");
	});


$app->run();
