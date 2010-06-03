<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(5, new lime_output_color());

// Create a test class
class DAOTest extends idlDynamicArrayObject {
  protected function getConstructionKeyMap(){
    return  array(
      'parent_id' => 'parent',
      'AlfrescoID' => 'alf_id',
      'toto' => 'toto'
    );
  }
  protected function __constructToto($value){
    return $value.'ti';
  }
  protected function __constructAlfId($value){
    $this->addNewProperty('name', $value.'z');
  }
}

// Test the constructor exceptions
try{new DAOTest('toto'); $t->fail("->__construct(): Accept to construct with somthing else than an array");}
catch (Exception $e){$t->pass("->__construct(): Exception: ".$e->getMessage());}
try{new DAOTest(array('???'=>'titi')); $t->fail("->__construct(): Accept undecalre key");}
catch (Exception $e){$t->pass("->__construct(): Exception: ".$e->getMessage());}

// Test object construction
$obj = new DAOTest(array('parent_id'=>17, 'AlfrescoID'=>'xy', 'toto' => 'ti' ));
$t->ok($obj->getParent()==17,"->__construct(): define the object property according to key");
$t->ok($obj->getToto()=='titi',"->__constructXXX(): allow to set a modified value to default constructor");
$t->ok($obj->getName()=='xyz',"->__constructXXX(): allow to bypass the default constructor");
