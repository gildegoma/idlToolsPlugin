<?php

include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
$t = new lime_test(3, new lime_output_color());

$t->is(idlDate::toTS("1980-11-08"), 342486000, "toTS() Convert date to TS");
$t->is(idlDate::toTS("1515-1-1"), -14358474000, "toTS() Convert big date to TS");
$t->is(idlDate::toTS("6099-12-31"), 130330162800, "toTS() Convert small date to TS");