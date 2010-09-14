<?php

class idlDate extends idlFunction {
	
	/**
	 * Return a TS of the provided date
   * @param string $date date
	 * @return integer
	 */
	public static function toTS($date){
	  $date = new DateTime($date);
		return $date->format('U');    
	}

}


  