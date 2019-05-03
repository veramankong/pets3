<?php
session_start();

//turn on error reporting
ini_set('display_errors', TRUE);
error_reporting(E_ALL);

//require autoload file
require_once("vendor/autoload.php");
require('model/validation-functions.php');

//create an instance of the base class
$f3 = Base::instance();
$f3->set('colors', array('pink', 'green', 'blue'));

//turn on fat-free error reporting
$f3->set('DEBUG', 3);

//define a default root
$f3->route('GET /', function () {
    //display a view
    $view = new Template();
    echo $view->render('views/home.html');
});

//route with parameter
$f3->route('GET /@item', function ($f3, $params) {
    $item = $params['item'];
    switch ($item) {
        case 'chicken':
            echo "<h3>Cluck</h3>";
            break;
        case 'dog':
            echo "<h3>Woof</h3>";
            break;
        case 'cat':
            echo "<h3>Meow</h3>";
            break;
        case 'giraffe':
            echo "<h3>Neck</h3>";
            break;
        case 'rat':
            echo "<h3>Skurry</h3>";
            break;
        default:
            $f3->error(404);
    }
});



//begin order
$f3->route('GET|POST /order', function($f3) {
    $_SESSION = array();
    if(isset($_POST['animal'])) {
        $animal = $_POST['animal'];
        if(validString($animal)) {
            $_SESSION['animal'] = $animal;
            $f3->reroute('/order2');
        }
        else {
            $f3->set("errors['animal']", "Please enter an animal");
        }
    }
    //display a view
    $view = new Template();
    echo $view->render('views/form1.html');
});

//second step of order
$f3->route('POST|GET /order2', function ($f3) {
    if(isset($_POST['color'])) {
        $color = $_POST['color'];
        if(validColor($color)) {
            $_SESSION['color'] = $color;
            $f3->reroute('/result');
        }
        else {
            $f3->set("errors['color']", "Please enter a color");
        }
    }
    $view = new Template();
    echo $view->render('views/form2.html');
});

//final step of order
$f3->route('POST|GET /result', function () {

    $view = new Template();
    echo $view->render('views/results.html');
});

//run fat-free
$f3->run();