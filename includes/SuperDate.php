<?php

  /*
   ==================================================================================
   Project     : seotrackz
   File        : seotrackz/includes/ASEO.php
   Description : Super Date functionality
   Parameters  : -
   Author      : -
   Date        : -
   ==================================================================================
   */

  class SuperDate {

		const PERIOD_HOUR='hour';
    const PERIOD_DAY='day';
    const PERIOD_WEEK='week';
    const PERIOD_MONTH='month';
    const PERIOD_YEAR='year';
    
    // default date format
    const DEFAULT_FORMAT="U";
    
    // Stores the date and time supplied
    protected $theDate, $badTimestamps;
    
    /**
     * Creates a date based on the current time.
     *
     * @return
     *   n/a
     */
    public function __construct() {
      date_default_timezone_set("Europe/London");
      //$this->setXmlFormat(Exporter::SINGLE_ELEMENT);
      $this->theDate = date(SuperDate::DEFAULT_FORMAT);
      
      //array of timestamps that relate to a mySQL date of '0000-00-00'
      //-62169984000 = '-0001-11-30' on a 64 bit system
      //943920000 = '1999-11-30' on a 32 bit system
      $this->badTimestamps = array(-62169984000, 943920000);
    }
    
    //is this a bad date or not?
    public function isBadDate() {
      if (in_array($this->getAsSeconds(), $this->badTimestamps)) {
        return true;
      }
      return false;
    }
    
    // Set the date of this SuperDate object to be based a supplied MySQL Date e.g. 2006-05-31 10:23:34
      public function initialiseMySQLDate($mysqlDate) {    
      $this->theDate = strtotime($mysqlDate);
      
      if ($this->isBadDate()) {
        $this->initialiseMySQLDate("1975-01-01 00:00:00");
      }   
    }
    
    // Set the date of this SuperDate object to be based a supplied English Date e.g. 31/5/2006 10:12:34
    public function initialiseEnglishDate($englishDate) {		

      // split the date and turn into US format mm/dd/yy hh:mm:ss because strtotime needs US date format
      if(preg_match("/^([0-9]+)\/([0-9]+)\/([0-9]+) (.+)/",$englishDate,$Matches)) {
        $day=$Matches[1];
        $month=$Matches[2];
        $year=$Matches[3];
        $time=$Matches[4];
        $usDate = sprintf("%s/%s/%s %s",$month,$day,$year,$time);
      } else {
        $usDate = 0;
      }

      // convert string to time
      $this->theDate = strtotime($usDate);     
    }
    
    // returns the current value as a MySQL date
    public function getAsMySQLDate() {
      return date("Y-m-d",$this->theDate);
    }
    
    // returns the current value as a MySQL date and time
    public function getAsTime() {
      return date("H:i:s",$this->theDate);      
    }
    
    // returns the current value as an English date
    public function getAsEnglishDate() {
      return date("d/m/Y",$this->theDate);
    }
    
    // returns the date in the form 'Monday, 27th January 2009'
    public function getAsString($format="l, jS F Y") {
      return date($format,$this->theDate);
    }
    
    // returns the current value as number of seconds since 1970
    public function getAsSeconds() {
      return $this->theDate;
    }

    public function getAsMySQLDateTime() {
         return $this->getAsMySQLDate()." ".$this->getAsTime();
    }

    public function equals($mysqldatetime) {
         if ($this->getAsMySQLDateTime() == $mysqldatetime) {
            return true;   
         } 
         return false;
    }
    
    // returns the current value in RFC2822 format e.g. Mon, 15 Aug 2005 15:12:46 +1:00 
    public function getAsRFC2822() {
      return date("r",$this->theDate);
    }

    // calculates a new date and returns it a MYSQL Format e.g calculateNewDate("year","+1"); 
    public function calculateNewDate($periodType,$period) {
        assert($periodType==SuperDate::PERIOD_HOUR || 
        		 $periodType==SuperDate::PERIOD_DAY ||
             $periodType==SuperDate::PERIOD_WEEK ||
             $periodType==SuperDate::PERIOD_MONTH  ||
             $periodType==SuperDate::PERIOD_YEAR );
          return date("Y-m-d H:i:s",strtotime($period." ".$periodType,strtotime($this->getAsMySQLDateTime())));
    }

    // updates the date based on a period and period type
    public function updateDate($periodType,$period) {
          $this->initialiseMySQLDate($this->calculateNewDate($periodType,$period));
    }

    // find the diffrence between the date set and the date now, in days
    public function dateDiff() {
       $dateDiff = strtotime($this->getAsMySQLDate()) - strtotime(date('Y-m-d'));
       return floor($dateDiff/(60*60*24));
    }

    // find out if the date is hot
    public function isItHot() {
       if ($this->dateDiff() >= 0) {
          return 1;
       }
       return 0;
    }

    // overriden to provide date in various formats
    public function exportAsXML() {
      return sprintf("<%s mysql=\"%s\" time=\"%s\" english=\"%s\" seconds=\"%s\" rfc2822=\"%s\" />\n",
                     get_class($this),
                     $this->getAsMySQLDate(),
                     $this->getAsTime(),
                     $this->getAsEnglishDate(),
                     $this->getAsSeconds(),
                     $this->getAsRFC2822());
    }
    
    // convert MySQL data to friendly string
    static public function mySQLToString($str) {
      $s = new SuperDate();
      $s->initialiseMySQLDate($str);
      return $s->getAsString();
    }
    
    // get difference between now and this date in seconds
    public function dateDiffSeconds() {
      $x = new SuperDate();
      $now = $x->getAsSeconds();
      $then = $this->getAsSeconds();
      return $now - $then;
    }
    
  }

?>