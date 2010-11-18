<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');

$t = new lime_test(6, new lime_output_color());

$t->is(idlFile::getExtention('toto.doc'), 'doc', "->getExtention() Work on simple file name");
$t->is(idlFile::getExtention('toto'), '', "->getExtention() Doesn't alter a filename without extension");
$t->is(idlFile::getExtention('toto.doc.xls'), 'xls', "->getExtention() Work with filename with multiple points");

$t->is(idlFile::removeExtension('toto.doc'), 'toto', "->removeExtension() Work on simple file name");
$t->is(idlFile::removeExtension('toto'), 'toto', "->removeExtension() Doesn't alter a filename without extension");
$t->is(idlFile::removeExtension('toto.doc.xls'), 'toto.doc', "->removeExtension() Work with filename with multiple points");


