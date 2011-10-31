<?php

/**
 * Ickle MVC
 * IckleRegistry Object: cut down for twilio demo app
 * @author Michael Peacock
 */
class IckleRegistry {
	
	/**
	 * Array of objects contained within the registry
	 */
	private $objects = array();
	
	/**
	 * Settings and config for core registry objects, for lazy loading
	 * @var array
	 */
	private $objectSetup = array();
	
	/**
	 * Array of settings contained within the registry
	 */
	private $settings = array();
	
	/**
	 * Constructor
	 * @return void
	 */
	public function __construct( $objectSetup=array() )
	{
		$this->objectSetup = $objectSetup;
	}
	
	/**
	 * prevent cloning of the object: issues an E_USER_ERROR if this is attempted
	 */
	public function __clone()
	{
		trigger_error( 'Cloning the registry is not permitted', E_USER_ERROR );
	}
	
	/**
	 * Store an object in the registry
	 * @param Object $object
	 * @param String $key
	 * @return void
	 */
	public function storeObject( $object, $key )
	{
		if( in_array( $key, $this->objects ) )
		{
			//throw new Exception();
		}
		else
		{
			if( is_object( $object ) )
			{
				$this->objects[ $key ] = $object;
			}
		}
	}
	
	/**
	 * Get an object from the registry
	 * - facilitates lazy loading, if we haven't used the object yet and it is part of the setup, then require and instantiate it!
	 * @param String $key
	 * @return Object
	 */
	public function getObject( $key )
	{
		if( in_array( $key, array_keys( $this->objects ) ) )
		{
			return $this->objects[$key];
		}
		elseif( in_array( $key, array_keys( $this->objectSetup ) ) )
		{
			if( ! is_null( $this->objectSetup[ $key ]['abstract'] ) )
			{
				require_once( FRAMEWORK_PATH . 'registry/aspects/' . $this->objectSetup[ $key ]['folder'] . '/' . $this->objectSetup[ $key ]['abstract'] .'.abstract.php' );
			}
			require_once( FRAMEWORK_PATH . 'registry/aspects/' . $this->objectSetup[ $key ]['folder'] . '/' . $this->objectSetup[ $key ]['file'] . '.class.php' );
			$o = new $this->objectSetup[ $key ]['class']( $this );
			$this->storeObject( $o, $key );
			return $o;
		}
		
	}
	
	/**
	 * Store a setting in the registry
	 * @param String $setting
	 * @param String $key the key to assign the setting
	 * @return void
	 */
	public function storeSetting( $setting, $key )
	{
		$this->settings[ $key ] = $setting;
	}
	
	/**
	 * Get a setting from the registry
	 * - uses lazy loading, if it isn't core and hasn't been accessed before, get all settings
	 * - from that group from the database, and store them, then get it
	 * @param String $key
	 * @param bool $lazyLoad [set to false if we haven't found it and then we looked it up in the DB - this prevents an infinate loop if it wasnt in the DB either, as this is recursively called]
	 * @return String
	 */
	public function getSetting( $key, $lazyLoad=true )
	{
		if( in_array( $key, array_keys( $this->settings ) ) )
		{
			return $this->settings[ $key ];
		}
		elseif( $lazyLoad )
		{
			$keyBits = explode( '_', $key );
			$sql = "SELECT * FROM settings WHERE `key`='" . $keyBits[0] ."' OR `key` LIKE '" . $keyBits[0] ."\_%' ";
			$this->getObject('db')->executeQuery( $sql );
			if( $this->getObject('db')->getNumRows() > 0 )
			{
				while( $row = $this->getObject('db')->getRows() )
				{
					$this->storeSetting( $row['value'], $row['key']);
				}
			}
			return $this->getSetting( $key, false );
		}
		else
		{
			return null;
		}
	}
	
	public function notify( $type, $heading, $message )
	{
		$_SESSION['notification_message'] = array( 'type' => $type, 'heading'=> $heading, 'message' => $message );
	}
	
	
	
	
}

?>