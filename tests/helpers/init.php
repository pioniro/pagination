<?php

namespace Pioniro\Pagination\Test;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

function initDoctrine()
{

// Create a simple "default" Doctrine ORM configuration for Annotations
    $isDevMode = true;
    $proxyDir = null;
    $cache = null;
    $useSimpleAnnotationReader = false;
    $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);
// database configuration parameters
    $conn = array(
        'driver' => 'pdo_pgsql',
        'dbname' => getenv('POSTGRES_NAME'),
        'user' => getenv('POSTGRES_USER'),
        'password' => getenv('POSTGRES_PASSWORD'),
        'host' => getenv('POSTGRES_HOST'),
        'port' => getenv('POSTGRES_PORT'),
    );
// obtaining the entity manager
    return EntityManager::create($conn, $config);
}