<?php
$loader = include "vendor/autoload.php";
use Symfony\Component\Yaml\Parser;
use Fmizzell\Systemizer\CodeGenerators\Object;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

error_reporting(E_ERROR);


$yaml = new Parser();
$appConfig = $yaml->parse(file_get_contents('app/config/app.yml'));
$namespace = "{$appConfig['Creator']}\\{$appConfig['Name']}";
$loader->addPsr4($namespace . "\\", 'app/src/');

// Create a src directory if it does not exist.
$fs = new Filesystem();
try {
    $fs->mkdir('app/src');
} catch (IOExceptionInterface $e) {
    echo "An error occurred while creating your directory at " . $e->getPath();
}

$configObjects = scandir('app/config/objects');
$configObjects = array_slice($configObjects, 2);

foreach($configObjects as $configObject) {
    $config = $yaml->parse(file_get_contents("app/config/objects/{$configObject}"));
    $metadata = Fmizzell\Systemizer\Metadata\Object::createFromArray($config);
    $generator = new Object($metadata);
    $generator->setNamespace($namespace);
    $fs->dumpFile("app/src/{$config['Name']}.php", "<?php\n" . $generator->generate());
}


