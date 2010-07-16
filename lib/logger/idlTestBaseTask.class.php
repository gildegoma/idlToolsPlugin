<?php

/**
 * This class allow to get the dev log on the test console.
 *  All the log generate with the idlLogger::dev() command will be display on the test console
 *  
 *  THIS CAN BE USED FOR DEBUG PURPOSE AND SHOULD NOT BE COMMIT AS THIS
 *  
 *  
 * To use it, temporary remove the base class of sfTestBaseTask with this one.
 * Location is in lib/vendor/symfony/lib/task/test/sfTestBaseTask.class.php
 * @author david
 *
 */
abstract class idlTestBaseTask extends sfBaseTask {
  
  public function __construct(sfEventDispatcher $dispatcher, sfFormatter $formatter) {
    parent::__construct($dispatcher, $formatter);
    idlLogger::getInstance()->startLogOnTest($dispatcher);
  }

}