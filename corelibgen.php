#!/usr/bin/php
<?php

require_once 'vendor/autoload.php';


$definitions = new \Clapp\CommandLineArgumentDefinition(array(
    "help|h"        => "Shows help message",
    "name|n=s"      => "name of component to generate",
    "dir|d=s"       => "base dir of where to create files",
    "namespace|s=s" => "base namespace of classes",
));

// Filter arguments based and validate according to definitions
$filter = new \Clapp\CommandArgumentFilter($definitions, $argv);

$valid = true;

$name = $filter->getParam("name");
if ($name === null) {
    $valid = false;

   echo "You need to provide a name\n\n"; 
} //if

$dir = $filter->getParam("dir");
if ($dir === null) {
    $valid = false;

   echo "You need to provide a base directory\n\n"; 
} //if

$namespace = $filter->getParam("namespace");
if ($namespace === null) {
    $valid = false;

   echo "You need to provide a namespace\n\n"; 
} //if

// Retrieve parameter if set
if ($filter->getParam('h') === true || $valid === false) {
    echo "Usage {$argv[0]} [options]\n\n";
    echo $definitions->getUsage();
    exit;
} //if



$twig = new Twig_Environment(new Twig_Loader_Filesystem(__DIR__ .'/templates'));

$globalTemplateVars = array(
    "moduleName"  => $name,
    "namespace"   => $namespace,
    "authorName"  => "Patrick Forget",
    "authorEmail" => "patforg@geekpad.ca",

);

echo $twig->render('bo.twig', array_merge($globalTemplateVars, array(
    "className" =>"{$name}BO",
)));

echo "\n\n";
