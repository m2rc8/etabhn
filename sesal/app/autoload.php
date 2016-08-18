<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../vendor/os/php-excel/PHPExcel/PHPExcel.php';
// intl
if (!function_exists('intl_get_error_code')) 
{
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->add('', __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
    $loader->add('Html2Pdf_',  __DIR__.'/../vendor/html2pdf/lib');
}

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
