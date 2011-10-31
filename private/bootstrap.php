<?php

define( "FRAMEWORK_PATH", dirname( __FILE__ ) ."/" );

/**
 * Ickle MVC Bootstrap file
 * Performs core includes, registry and object setup and request process
 * @author Michael Peacock
 */
class Bootstrap
{
	/**
	 * Registry object
	 */
	private $registry;
	

	
	/**
	 * Bootstrap constructor
	 * @return void
	 */
	public function __construct()
	{
		session_name('icklemvc');
		session_regenerate_id();
		
		$defaultRegistryObjects = array();
		$db = array( 'abstract' => 'database', 'folder' => 'database', 'file' => 'mysql.database', 'class' => 'MySQLDatabase', 'key' => 'db' );
		$defaultRegistryObjects['db'] = $db;
		
		$urlp = array( 'abstract' => null, 'folder' => 'urlprocessor', 'file' => 'urlprocessor', 'class' => 'URLProcessor', 'key' => 'urlprocessor' );
		$defaultRegistryObjects['urlprocessor'] = $urlp;
		
		
		require_once( FRAMEWORK_PATH . 'registry/registry.class.php' );
		$this->registry = new IckleRegistry( $defaultRegistryObjects );
		$this->defaultRegistrySetup();
		
		$this->notifications();
		require_once( FRAMEWORK_PATH . 'controllers/front/front.controller.php' );
		$fc = new Frontcontroller( $this->registry );
		$fc->process();
		
		$this->preParse();
		$this->registry->getObject('template')->parseOutput();
		print $this->registry->getObject('template')->getPage()->getContentToPrint();
		
	}
	
	private function notifications()
	{
		
	}
	
	/**
	 * Setup and store the core, default registry objects
	 * @return void
	 */
	private function defaultRegistrySetup()
	{
		$db_credentials = array();
		require_once( FRAMEWORK_PATH . 'config.php' );
		$this->registry->getObject('db')->newConnection( $db_credentials['default_host'], $db_credentials['default_user'], $db_credentials['default_password'], $db_credentials['default_database'] );
	}
	
	private function newPreParse()
	{
		// new pre parse method for use in the new fleet manager
	}
	
	private function preParse()
	{
	}
	
	/**
	 * Get the registry object
	 * @return Object
	 */
	public function getRegistry()
	{
		return $this->registry;
	}
	
	
}

?>