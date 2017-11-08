<?php

require_once __DIR__ . '/vendor/autoload.php';

if (!empty($argv[1])) {
	switch ($argv[1]) {
		case 'case1':
			echo 'Test case1: method_exists($orgValue, \'__toString\')'.PHP_EOL;
			require_once __DIR__ .'/cases/1UnitOfWork.php';
			break;
		case  'case2':
			echo 'Test case2: Doctrine\DBAL\Types\Type::isValuesIdentical($old, $new)'.PHP_EOL;
			require_once __DIR__ .'/cases/2Type.php';
			require_once __DIR__ .'/cases/2DateTimeType.php';
			require_once __DIR__ .'/cases/2UnitOfWork.php';
			break;
		default:
			echo 'Test case default'.PHP_EOL;
			break;
	}
} else {
	echo 'Test case default'.PHP_EOL;
}

use Doctrine\Common\ClassLoader;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Types\Type;



$loader = new ClassLoader('Testing', __DIR__);
$loader->register();

$config = Setup::createConfiguration(true);
$config->setMetadataDriverImpl(new XmlDriver( __DIR__ . '/Testing' ));
$conn = DriverManager::getConnection(array('driver'   => 'pdo_sqlite'), $config);

Type::addType('point', 'Testing\PointType');

$em   = EntityManager::create($conn, $config, $conn->getEventManager());
$uof  = $em->getUnitOfWork();
$meta = $em->getClassMetadata('Testing\Entity');

$time = marker();

// Creat bunch of entities
$dummyNum = 10000;
$entities = [];
for ($i = 0; $i < $dummyNum; $i++) {
	$entity = getEntityWithRandomData();
	$entity->id = $i;

	$data = (array) getEntityWithRandomData();

	$uof->registerManaged($entity, array($entity->id), $data);
	$entities[] = $entity;
}
echo sprintf('Dummies %s items', $dummyNum).PHP_EOL;

$time = marker($time, 'Make dummies: %s');

$uof->computeChangeSets();

$time = marker($time, 'Compute changes: %s');

foreach ($entities as $entity) {
	$uof->recomputeSingleEntityChangeSet($meta, $entity);
}

$time = marker($time, 'Recompute changes: %s');


function getEntityWithRandomData() {
	$entity = new Testing\Entity;
	$entity->state = rand(0, 10);
	$entity->title = uniqid(rand(0, 1000));
	$entity->text  = uniqid(rand(1000, 10000), true);
	$entity->array = array_fill(0, rand(5, 100), uniqid(rand(0, 1000)));

	$entity->created  = new DateTime('now');
	$entity->modified = new DateTime('now');

	$entity->pont1 = new Testing\Point(rand(0, 100)/100, rand(0, 100)/100);
	$entity->pont2 = new Testing\Point(rand(0, 100)/100, rand(0, 100)/100);

	return $entity;
}

function marker($prevTime = 0, $msg = '') {
	$mtime = microtime();
	$mtime = explode(' ', $mtime);
	$mtime = $mtime[1] + $mtime[0];
	$time  = $mtime;

	if ($prevTime && $msg) {
		$totaltime = $time - $prevTime;
		echo sprintf($msg, $totaltime).PHP_EOL;
	}

	return $time;
}
