<?php

if( !class_exists( 'Project041_Definitions' ) ) {

	class Project041_Definitions {

		private static $_this;

		private static $_version;

		public static $scripts_version;

		public static $mailchimp;

		function __construct() {
		
			self::$_this = $this;

			self::$scripts_version = '1.1.14';		

			self::$mailchimp = array(
				'list_id' => 'c16c07506f',
				'api_key' => 'bbff56216e87b4e1e93d2dbf638d0018-us17',
				'url' => '//project041.us17.list-manage.com/subscribe/post-json?u=9039f9db50c0fbd27be411ff8&id=c16c07506f'
			);

		}

		static function this() {
		
			return self::$_this;
		
		}

	}

}

new Project041_Definitions();