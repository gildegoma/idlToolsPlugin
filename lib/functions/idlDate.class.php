<?php

class idlDate extends idlFunction {
	
	/**
	 * Return a TS of the provided date
	 *  By default the timestamp is return as a string to avoid int overflow problem. But, you can request 
	 *   to get the value as int. At that moment, if the date could create an int overflow, then an exception
	 *   is thrown
   * @param string $date date
	 * @return string|int
	 */
	public static function toTS($date, $useInt=false){
	  $date = new DateTime($date);
	  // Return as a string
	  if ($useInt==false){
		  return $date->format('U');
	  }
	  // Try to return as a string
	  $strVal = $date->format('U');
	  $PHP_INT_MIN = (int)(PHP_INT_MAX+1);
	  if ($strVal > PHP_INT_MAX || $strVal < $PHP_INT_MIN){
	    throw new Exception("Impossible to convert the date $date in integer timestamp, as the current system limitations are reach");
	  }
    return (int) $date->format('U');
	}

}


  