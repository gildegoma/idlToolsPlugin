<?php 

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(11, new lime_output_color());

$obj = new idlDynamicObject();
$obj->addNewProperty('name', 'toto');

// addNewProperty($name, $value){
try{$obj->addNewProperty('name', 'titi'); $t->fail("->addNewProperty(): must refuse duplicate property");}
catch (Exception $e){$t->pass("->addNewProperty(): refuse duplicate property");}

// Getter
$t->is($obj->getName(),'toto', "->getXXX() Return the property");
try{$obj->getFirstName(); $t->fail("->getXXX(): must throw exception when accessing invalid property");}
catch (Exception $e){$t->pass("->getXXX(): throw exception when accessing invalid property");}


// Setter
$obj->setName('titi');
$t->is($obj->getName(),'titi', "->setXXX() Allow to modify the property");
try{$obj->setFirstName(); $t->fail("->setXXX(): must throw exception when accessing invalid property");}
catch (Exception $e){$t->pass("->setXXX(): throw exception when accessing invalid property");}


// function hasXXX
$t->ok($obj->hasName(), "->hasXXX() Return true for valid property");
$t->ok(!$obj->hasFirstName(), "->hasXXX() Return false for invalid property");


// removeProperty($name)
$obj->removeProperty('name');
$t->ok(!$obj->hasName(), "->removeProperty() remove it");
$obj->addNewProperty('name', 'toto');
$t->ok($obj->hasName(), "->removeProperty() Then it's possible to add it again");

// disableProperty($name) enableProperty($name)
$obj->disableProperty('name');
$t->ok(!$obj->hasName(), "->disableProperty() works");
$obj->enableProperty('name');
$t->ok($obj->hasName(), "->enableProperty() works");

