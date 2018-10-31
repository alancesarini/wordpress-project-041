<?php

if( !class_exists( 'Project041_Calendar' ) ) {
    
    class Project041_Calendar {  

        private static $_version;
        
        public static $prefix;

        private static $naviHref;

        private static $dayLabels;
        
        private static $currentYear=0;
        
        private static $currentMonth=0;
        
        private static $currentDay=0;
        
        private static $currentDate=null;
        
        private static $daysInMonth=0;

        private static $weekdaysInMonth=0;

        private static $dayOfTheWeek;

        /**
         * Constructor
        */
        public function __construct() {

            self::$_version = '1.0.0';
            
            self::$prefix = '_project041_calendar_';

            self::$naviHref = htmlentities($_SERVER['PHP_SELF']);

            // Spanish locale
            setlocale(LC_TIME, 'es_ES');  
            //setlocale(LC_ALL,'es_ES');
            
            // Ajax action to load the prev/next month
			add_action( 'wp_ajax_project041-load-calendar', array( $this, 'load_calendar' ) );			
            add_action( 'wp_ajax_nopriv_project041-load-calendar', array( $this, 'load_calendar' ) );

            self::$dayLabels = array(
                _x( 'Lun', 'project-041' ),
                _x( 'Mar', 'project-041' ),
                _x( 'Mie', 'project-041' ),
                _x( 'Jue', 'project-041' ),
                _x( 'Vie', 'project-041' ),
                _x( 'Sab', 'project-041' ),
                _x( 'Dom', 'project-041' )
            );
            
        }
            
        /**
        * print out the calendar
        */
        public function show() {
            $year  == null;
            
            $month == null;
            
            if(null==$year&&isset($_GET['year'])){
    
                $year = $_GET['year'];
            
            }else if(null==$year){
    
                $year = date("Y",time());  
            
            }          
            
            if(null==$month&&isset($_GET['month'])){
    
                $month = $_GET['month'];
            
            }else if(null==$month){
    
                $month = date("m",time());
            
            }                  
            
            $content = '<div id="calendar">' . self::get_calendar_code( $year, $month ) . '</div>';

            echo $content;   

        }

        public function load_calendar() {

            $year = intval( $_POST['yy'] );
        
            $month = intval( $_POST['mm'] );
                     
            $content = self::get_calendar_code( $year, $month );
                    
            echo $content;   
            die();
        }

        private function get_calendar_code( $year, $month ) {
            
            self::$currentYear=$year;
            
            self::$currentMonth=$month;
            
            self::$daysInMonth=self::_daysInMonth($month,$year); 
                        
            $content = '<div class="box">'.
                            self::_createNavi().
                            '</div>'.
                            '<div class="box-content">'.
                                    '<ul class="label">'.self::_createLabels().'</ul>';   
                                    $content.='<div class="clear"></div>';     
                                    $content.='<ul class="dates">';    

                                    $weeksInMonth = self::_weeksInMonth($month,$year);
                                    // Create weeks in a month
                                    for( $i=0; $i<$weeksInMonth; $i++ ){

                                        //Create days in a week
                                        for($j=1;$j<=7;$j++){
                                            $content.=self::_showDay($i*7+$j);
                                        }
                                    }

                                    $content.='</ul>';

                                    $content.='<div class="clear"></div>';     
                                
                            $content.='</div>';
            return $content;
        }

        /**
        * create the li element for ul
        */
        private function _showDay($cellNumber){

            $mobile_detect = new Mobile_Detect();

            $cell_class = '';
            $pro = '';
            $array_weekend = array(6, 7, 13, 14, 20, 21, 27, 28, 34, 35, 41, 42 );
            if( in_array( $cellNumber, $array_weekend ) ) {
                $cell_class = ' weekend ';
            }

            if(self::$currentDay==0){
                
                $firstDayOfTheWeek = date('N',strtotime(self::$currentYear.'-'.self::$currentMonth.'-01'));
                        
                if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                    
                    self::$currentDay=1;
                    
                }
            }
            
            if( (self::$currentDay!=0)&&(self::$currentDay<=self::$daysInMonth) ){
                
                self::$currentDate = date('Y-m-d',strtotime(self::$currentYear.'-'.self::$currentMonth.'-'.(self::$currentDay)));

                $events = Project041_Event::get_events_by_date( self::$currentDate );

                $cellContent = '<span class="number">' . self::$currentDay . '</span>';
                
                if( count( $events ) > 0 ) {
                    $events_text = '';
                    $event_city = '';

                    foreach( $events as $event ) {
                        $event_name = $event->name;
                        /*if( $mobile_detect->isMobile() ) {
                            $event_name = substr( $event_name, 0, 25 ) . ' ...';
                        } */           
                        //$events_text .= '<a href="' . $event->url . '" data-tooltip-content="#popup-content" class="event-action" data-text="' . $event->excerpt . '" data-title="' . $event->title . '">' . $event_name . '</a><br>';
                        $events_text .= '<a href="' . $event->url . '" class="event-action">' . $event_name . '</a><br>';
                        $event_city = '<div style="background-image:url(' . $event->citypic['sizes']['thumbnail'] . ')"></div>';
                    }
                    $cellContent .= '<span class="text">' . $events_text . '</span>';
                    $legend = '<span class="legend">';
                    if( $event->is_for_professionals ) {
                        //$legend .= '<span class="legend-blue"></span>';
                        $pro = '<span class="pro"></span>';
                    }
                    /*
                    if( !$events[0]->is_external ) {
                        $legend .= '<span class="legend-red"></span>';
                    } 
                    */                   
                    $legend .= '</span>';
                }   

                self::$currentDay++;   
                
            }else{

                self::$currentDate =null;
    
                $cellContent=null;
            }
                
            $array_start = array(1, 6, 11, 16, 21, 26);
            $array_end = array(5, 12, 19, 26, 33, 40);
            if( in_array( $cellNumber, $array_start ) ) {
                $cell_class .= ' start ';
            }
            if( in_array( $cellNumber, $array_end ) ) {
                $cell_class .= ' end ';
            }            
            return '<li id="li-' . self::$currentDate . '" class="' . $cell_class .
                    ($cellContent==null?'mask':'').'">' . $legend . $cellContent . $event_city . $pro . '</li>';
        }
        
        /**
        * create navigation
        */
        private function _createNavi(){
            
            $nextMonth = self::$currentMonth==12?1:intval(self::$currentMonth)+1;
            
            $nextYear = self::$currentMonth==12?intval(self::$currentYear)+1:self::$currentYear;
            
            $preMonth = self::$currentMonth==1?12:intval(self::$currentMonth)-1;
            
            $preYear = self::$currentMonth==1?intval(self::$currentYear)-1:self::$currentYear;
            
            return
                '<div class="header">'.
                    '<a class="prev" href="'.self::$naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'" data-month="' . sprintf('%02d',$preMonth) . '" data-year="' . $preYear . '"><i class="fa fa-chevron-left"></i></a>'.
                        '<span class="title">'.strftime('%B %G',strtotime(self::$currentYear.'-'.self::$currentMonth.'-1')).'</span>'.
                    '<a class="next" href="'.self::$naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'" data-month="' . sprintf('%02d',$nextMonth) . '" data-year="' . $nextYear . '"><i class="fa fa-chevron-right"></i></a>'.
                '</div>';
        }
            
        /**
        * create calendar week labels
        */
        private function _createLabels(){  
                    
            $content='';
            
            foreach(self::$dayLabels as $index=>$label){
                
                $class = '';
                if( $index > 4 ) {
                    $class = ' weekend ';
                }
                $content.='<li class="' . $class .($label==6?'end title':'start title').' title">'.$label.'</li>';
    
            }
            
            return $content;
        }
        
        
        
        /**
        * calculate number of weeks in a particular month
        */
        private function _weeksInMonth($month=null,$year=null){
            
            if( null==($year) ) {
                $year =  date("Y",time()); 
            }
            
            if(null==($month)) {
                $month = date("m",time());
            }
            
            // find number of days in this month
            $daysInMonths = self::_daysInMonth($month,$year);
            
            $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
            
            $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));
            
            $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));
            
            if($monthEndingDay<$monthStartDay){
                
                $numOfweeks++;
            
            }
            
            return $numOfweeks;
        }
    
        /**
        * calculate number of days in a particular month
        */
        private function _daysInMonth($month=null,$year=null){
            
            if(null==($year))
                $year =  date("Y",time()); 
    
            if(null==($month))
                $month = date("m",time());
                
            return date('t',strtotime($year.'-'.$month.'-01'));
        }


        private function _weekdaysInMonth($y, $m, $ignore = false) {
            $result = 0;
            $loop = strtotime("$y-$m-01");
            do if(!$ignore or !in_array(strftime("%u",$loop),$ignore))
                $result++;
            while(strftime("%m",$loop = strtotime("+1 day",$loop))==$m);
            return $result;
        }        

        
    }
    
}    

new Project041_Calendar();