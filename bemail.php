<?php
class Bemail extends BemailsAppModel {
	
	/***
	* Returns an array of holiday dates.
	**/
	public function holidayDates() {
		$holidays = array();
        $year = date('Y');

            $holidays[] = $year . '-1-1'; // New Years Day
            //$holidays[] = $year . '-2-6'; // today, just for testing
            $holidays[] = $year . '-2-10'; // returns 2013-02-17 date('Y-n-j', strtotime($year . '-02-00, second monday')); // Family Day
            $holidays[] = date('Y-n-j', strtotime('-2 days', easter_date($year)));   // Good Friday
            $holidays[] = date('Y-n-j', strtotime('+1 days', easter_date($year)));   // Easter Monday
            $holidays[] = date('Y-n-j', strtotime($year . '-05-25, last monday'));   // Victoria Day
            $holidays[] = $year . '-7-1';                                          // Canada Day
            $holidays[] = date('Y-n-j', strtotime($year . '-08-00, first monday'));  // Civic Holiday
            $holidays[] = date('Y-n-j', strtotime($year . '-09-00, first monday'));  // Labour Day
            $holidays[] = date('Y-n-j', strtotime($year . '-10-00, second monday')); // Thanksgiving
            $holidays[] = $year . '-11-11';                                          // Remembrance Day
            $holidays[] = $year . '-12-25';                                          // Christmas
            $holidays[] = $year . '-12-26';                                          // Boxing Day
            $holidays[] = $year . '-7-12';                                          // Fun Day
            $holidays[] = $year . '-12-27';                                          // Fun Day
            $holidays[] = $year . '-12-24';                                          // Fun Day
            $holidays[] = $year . '-11-12';                                          // Fun Day
            if( date( 'N' ) == 6 || ( date( 'G' ) > 13 && date( 'N' ) == 5 ) ) 
            {
            	$holidays[] = date( 'Y-n-j' , strtotime( '+2 days' ) );
            }
            if( date( 'N' ) == 7 || date( 'G' ) > 13 ) 
            {
            	$holidays[] = date( 'Y-n-j' , strtotime( '+1 days' ) );
            }

			//this code doesn't do anything. Sigh. 
        foreach ($this->Bemail->exceptionDays as &$holiday) {
            $holiday = date('Y-n-j', strtotime($holiday));
        }
		return $holidays;
		
	}
	
	/***
	* Returns an array of dates that fall on the weekend for the next year.
	**/
	public function weekendDates() {
				
		// prevent multiple calls by retrieving time once //
		$now = time();
		$aYearLater = strtotime('+1 Year', $now);

		// fill this with dates //
		$allDates = Array();

		// init with next friday and saturday //
		$sunday = strtotime('Next Sunday', strtotime('-1 Day', $now));
		$saturday = strtotime('Next Saturday', strtotime('-1 Day', $now));

		// keep adding days untill a year has passed //
		while(1){
			if($sunday > $aYearLater)
				break 1;
			$allDates[] = date('Y-n-j', $sunday);
			if($saturday > $aYearLater)
				break 1;
			$allDates[] = date('Y-n-j', $saturday);

			$sunday = strtotime('+1 Week', $sunday);
			$saturday = strtotime('+1 Week', $saturday);
		}
		
		return $allDates;

	}
	
	
	
	/***
	* Returns "tomorrow" in relation to the given day, not in relation 
	* to the actual today that you're currently in, as given by time().
	*/
	
	public function tomorrow($today) {
		return $today + 86400;
	}
	
	
	/**
	* Returns the next available date in unix timestamp format.
	*/
	public function nextAvail($givenDate, $kill = FALSE) {
		
		$hols = $this->holidayDates();
		$weekends = $this->weekendDates();
		$today = time();
		$tomorrow = $this->tomorrow($givenDate);

		/*	commented out because another function in the view or controller appears to be interfering with this.
	
		if(date('H', $today) >= 13 && $kill == FALSE) {
			$kill = TRUE;
			$this->nextAvail($tomorrow, $kill); //if it's afternoon today, recurse ONCE more.
			return;
		}
		*/
		//in_array($needle, $haystack);
		if(in_array(date('Y-n-j', $tomorrow), $hols)) { //if tomorrow is a holiday, recurse
			//mail('shannon@radarhill.com', '354', date('Y-n-j', $tomorrow) . " is a holiday");
			$this->nextAvail($tomorrow, $kill);
			return;
		}
		
		if(in_array(date('Y-n-j', $tomorrow), $weekends)) { //if tomorrow is a weekend, recurse
			$this->nextAvail($tomorrow, $kill);
			return;
		}
		
		$this->nextAvail = $tomorrow;
		return $tomorrow;
		
	}
	

}
