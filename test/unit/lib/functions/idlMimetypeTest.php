<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');

$t = new lime_test(5, new lime_output_color());

// Init
$t->ok(is_file(idlMimetype::getRefFilePath()), "->getRefFilePath() Return a valid file path");
$t->ok(count(idlMimetype::getAll()) > 150, "->getAll() Return a big list of mimetypes");

// Get extention
$t->is(idlMimetype::getFromExtention('doc'), 'application/msword', "->getFromExtention() Return a valid value for 'doc' extension");
$t->is(idlMimetype::getFromExtention('DOC'), 'application/msword', "->getFromExtention() Return a valid value for uppercase 'DOC' extension");
$t->is(idlMimetype::getFromExtention('xxx'), 'application/octet-stream', "->getFromExtention() Return a valid value for invalid extention");

