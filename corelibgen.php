#!/usr/bin/php
<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/functions.php';


$definitions = new \Clapp\CommandLineArgumentDefinition(array(
    "help|h"        => "Shows help message",
    "name|n=s"      => "name of component to generate",
    "dir|d=s"       => "base dir of where to create files",
    "namespace|s=s" => "base namespace of classes",
    "table|t=s"     => "MySQL table name",
    "stdout|o"   => "show code to stdout instead of writing files",
    "bo-only|b"   => "only create BO",
    "collection-only|c"   => "only create collection",
    "factory-only|f"   => "only create factory",
    "dao-only|r"   => "only create DAO",
    "dso-only|x"   => "only create DSO",
));

// Filter arguments based and validate according to definitions
$filter = new \Clapp\CommandArgumentFilter($definitions, $argv);

if ($filter->getParam('help') === true) {
    errorMessage("Usage {$argv[0]} [options]\n\n");
    errorMessage($definitions->getUsage());
    exit;
} //if

$valid = true;
$boOnly = $filter->getParam("bo-only");
$collectionOnly = $filter->getParam("collection-only");
$factoryOnly = $filter->getParam("factory-only");
$daoOnly = $filter->getParam("dao-only");
$dsoOnly = $filter->getParam("dso-only");

$name = $filter->getParam("name");
if ($name === null) {
    $valid = false;

   errorMessage("You need to provide a name\n\n"); 
} //if

$dir = $filter->getParam("dir");
if ($dir === null) {
    $valid = false;

    errorMessage("You need to provide a base directory\n\n"); 
} else {

    $dir = rtrim($dir, "/");

    if (!is_dir($dir)) {
        $valid = false;
        errorMessage("You need to provide a valid directory\n\n");
    } //if

} //if

$namespace = $filter->getParam("namespace");
if ($namespace === null) {
    $valid = false;

   errorMessage("You need to provide a namespace\n\n"); 
} //if

$tableName = $filter->getParam("table");
if ($namespace === null) {
    $valid = false;

   errorMessage("You need to provide a MySQL table\n\n"); 
} //if

$outputCode = $filter->getParam("stdout");

if ($valid === false) {
    errorMessage("Usage {$argv[0]} [options]\n\n");
    errorMessage($definitions->getUsage());
    exit(2);
} //if

$configFilename = __DIR__ . '/includes/config.php';
if (file_exists($configFilename)) {
    $config = include($configFilename);
} else {
    errorMessage("No config file found in includes/config.php\n\n");
    exit(2);
} //if

$twig = new Twig_Environment(new Twig_Loader_Filesystem(__DIR__ .'/templates'));

/* create namespace dir if it doesn't exist */

$namespaceDir = $dir . "/" . basename(str_replace("\\",'/', $namespace));
if (!$outputCode) {
    createMissingDir($namespaceDir);
} //if

$namespace = ltrim($namespace, "\\");

$globalTemplateVars = array_merge($config['templateVars'], array(
    "moduleName"  => $name,
    "namespace"   => $namespace,
    "tableName"   => $tableName,
));

/*---------------------------------------------------------------------------*
 *                              BUSINESS OBJECT                              *
 *---------------------------------------------------------------------------*/
if ($boOnly || !$collectionOnly && !$factoryOnly && !$daoOnly && !$dsoOnly) {
    $className = "{$name}BO";
    $filename = "{$namespaceDir}/Model/{$className}.php";

    $code =  $twig->render('bo.twig', array_merge($globalTemplateVars, array(
        "className" => $className,
    )));

    if ($outputCode) {
        echo $code,PHP_EOL;
    } else {
        createMissingDir($filename, false);
        file_put_contents($filename, $code);
        echo "Created {$filename}", PHP_EOL;
    } //if
    $code = null;
    unset($code);
} //if

/*---------------------------------------------------------------------------*
 *                               COLLECTION                                  *
 *---------------------------------------------------------------------------*/
if ($collectionOnly || !$boOnly && !$factoryOnly && !$daoOnly && !$dsoOnly) {
    $className = "{$name}Collection";
    $filename = "{$namespaceDir}/Model/{$className}.php";

    $code =  $twig->render('collection.twig', array_merge($globalTemplateVars, array(
        "className" => $className,
    )));

    if ($outputCode) {
        echo $code,PHP_EOL;
    } else {
        createMissingDir($filename, false);
        file_put_contents($filename, $code);
        echo "Created {$filename}", PHP_EOL;
    } //if

    $code = null;
    unset($code);
} //if

/*---------------------------------------------------------------------------*
 *                                 FACTORY                                   *
 *---------------------------------------------------------------------------*/
if ($factoryOnly || !$boOnly && !$collectionOnly && !$daoOnly && !$dsoOnly) {
    $className = "{$name}Factory";
    $filename = "{$namespaceDir}/{$className}.php";

    $code =  $twig->render('factory.twig', array_merge($globalTemplateVars, array(
        "className" => $className,
    )));

    if ($outputCode) {
        echo $code,PHP_EOL;
    } else {
        createMissingDir($filename, false);
        file_put_contents($filename, $code);
        echo "Created {$filename}", PHP_EOL;
    } //if

    $code = null;
    unset($code);
} //if

if ($daoOnly || !$boOnly && !$collectionOnly && !$factoryOnly && !$dsoOnly) {
    /*---------------------------------------------------------------------------*
     *                              DAO Interface                                *
     *---------------------------------------------------------------------------*/
    $className = "{$name}DAOInterface";
    $filename = "{$namespaceDir}/Data/{$className}.php";

    $code =  $twig->render('daoInterface.twig', array_merge($globalTemplateVars, array(
        "className" => $className,
    )));

    if ($outputCode) {
        echo $code,PHP_EOL;
    } else {
        createMissingDir($filename, false);
        file_put_contents($filename, $code);
        echo "Created {$filename}", PHP_EOL;
    } //if

    $code = null;
    unset($code);

    /*---------------------------------------------------------------------------*
     *                                DAO MySQL                                  *
     *---------------------------------------------------------------------------*/
    $className = "{$name}DAOMySQL";
    $filename = "{$namespaceDir}/Data/{$className}.php";

    $code =  $twig->render('daoMySQL.twig', array_merge($globalTemplateVars, array(
        "className" => $className,
    )));

    if ($outputCode) {
        echo $code,PHP_EOL;
    } else {
        createMissingDir($filename, false);
        file_put_contents($filename, $code);
        echo "Created {$filename}", PHP_EOL;
    } //if

    $code = null;
    unset($code);
} //if

if ($dsoOnly || !$boOnly && !$collectionOnly && !$factoryOnly && !$daoOnly) {
    /*---------------------------------------------------------------------------*
     *                              DSO Interface                                *
     *---------------------------------------------------------------------------*/
    $className = "{$name}DSOInterface";
    $filename = "{$namespaceDir}/Data/{$className}.php";

    $code =  $twig->render('dsoInterface.twig', array_merge($globalTemplateVars, array(
        "className" => $className,
    )));

    if ($outputCode) {
        echo $code,PHP_EOL;
    } else {
        createMissingDir($filename, false);
        file_put_contents($filename, $code);
        echo "Created {$filename}", PHP_EOL;
    } //if

    $code = null;
    unset($code);

    /*---------------------------------------------------------------------------*
     *                                DSO MySQL                                  *
     *---------------------------------------------------------------------------*/
    $className = "{$name}DSOMySQL";
    $filename = "{$namespaceDir}/Data/{$className}.php";

    $code =  $twig->render('dsoMySQL.twig', array_merge($globalTemplateVars, array(
        "className" => $className,
    )));

    if ($outputCode) {
        echo $code,PHP_EOL;
    } else {
        createMissingDir($filename, false);
        file_put_contents($filename, $code);
        echo "Created {$filename}", PHP_EOL;
    } //if

    $code = null;
    unset($code);
} //if

exit(0);
