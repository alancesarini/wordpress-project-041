<?php

// Constant definitions
require( 'includes/class_definitions.php' );

// General configuration
require( 'includes/class_configuration.php' );

// Common functions
require( 'includes/class_functions.php' );

// Backfoffice
require( 'includes/class_backoffice.php' );

// CPTs
require( 'includes/cpt/class_event.php' );
require( 'includes/cpt/class_chart.php' );
require( 'includes/cpt/class_conference.php' );
require( 'includes/cpt/class_magazine.php' );

// Calendar class
require( 'includes/class_calendar.php' );

// Class for detecting mobile devices
include( dirname( __FILE__ ) . '/includes/vendor/mobile_detect/Mobile_Detect.php' );




