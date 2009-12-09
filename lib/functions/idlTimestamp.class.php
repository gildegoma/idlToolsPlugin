<?php

class idlTimestamp extends idlFunction {
	
	/**
	 * Return a date formated according to ISO 8601
	 * Ref: http://www.iso.org/iso/fr/support/faqs/faqs_widely_used_standards/widely_used_standards_other/date_and_time_format.htm
   * @param int $ts Timestamp
	 * @return string
	 */
	public static function toISO8601Date($ts){
		return self::toDate($ts, 'Y-m-d');    
	}
	
	/**
	 * Convert a timestamp to a specific date format 
	 * @param $ts      Timestamp
	 * @param $format  Can be a predefine format, like 'ISO8601', or can be format string
	 *                  like 'yy.mm.dd' 
	 * @return string
	 */
  public static function toDate($ts, $format = 'ISO8601'){
  	// Try to use a local function
  	$function = array('idlTimestamp', 'to'.$format.'Date');
    if ( is_callable($function) ){
    	return call_user_func($function,$ts);
    }
    // Try to do a standard convert
    $date = @date($format, $ts);
    if ($date === false){
    	throw new Exception ("Impossible to convert the timestamp [$ts] to the date format [$format]");
    }
    return $date;    
  }

}


  