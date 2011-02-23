<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(1, new lime_output_color());

$t->is(strlen(idlRandom::getHumanString(11)), 11 , "->getHumanString() Return a string of the requested size");