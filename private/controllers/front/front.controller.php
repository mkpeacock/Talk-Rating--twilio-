<?php
/**
 * IckleMVC Front controller
 * 
 * @author Michael Peacock
 */
class Frontcontroller {
	
	/**
	 * Reference to IckleRegistry
	 * @var IckleRegistry
	 */
	private $registry;
	
	/**
	 * All active controllers
	 * @var array
	 */
	private $activeControllers = array();
	
	/**
	 * Dynamic, regexp mappings
	 * @var array
	 */
	private $dynamicMappings = array();
	
	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @return void
	 */
	public function __construct( IckleRegistry $registry, $autoProcess=false )
	{
		$this->registry = $registry;
		$this->activeControllers[] = 'smtwilio';
	}
	
	/**
	 * Set the active controllers in the site
	 * @param array $activeControllers
	 * @return void
	 */
	public function setActiveControllers( $activeControllers=array() )
	{
		$this->activeController = $activeControllers;
	}
	
	/**
	 * Set the dynamic mapping regexps
	 * @param array $dynamicMappings
	 * @return void
	 */
	public function setDynamicMappings( $dynamicMappings=array() )
	{
		$this->dynamicMappings = $dynamicMappings;
	}
	
	/**
	 * Process and route a request
	 * @return void
	 */
	public function process( $fallback=false )
	{
		require_once( FRAMEWORK_PATH . 'controllers/smtwilio/smtwilio.controller.php' );
		$smtwilio = new SMTwilioController( $this->registry, true );
		/**
		 * $bit0 = $this->registry->getObject('urlprocessor')->getURLBit(0);
		if( in_array( $bit0, $this->activeControllers ) && $fallback == false )
		{
			if( file_exists( FRAMEWORK_PATH . 'controllers/' . $bit0 . '/' . $bit0 . 'controller.php' ) )
			{
				require_once( FRAMEWORK_PATH . 'controllers/' . $bit0 . '/' . $bit0 . 'controller.php' );
				$controller = ucfirst( $bit0 ) . 'controller';
				$controller = new $controller( $this->registry, true );
			}
		}
		else
		{
			// @anothonysterling doesn't like this way, but I do so there :-p
			$match = false;
			foreach( $this->dynamicMappings as $mapping )
			{
				$path = $this->registry->getObject('urlprocessor')->getURLPath();
				if( preg_match( $mapping['pattern'], $path ) && in_array( $mapping['controller'], $this->activeControllers ) )
				{
					$match = true;
					require_once( FRAMEWORK_PATH . 'controllers/'. $mapping['controller'] . '/'. $mapping['controller'] . '.controller.php' );
					$controllerName = ucfirst( $mapping['controller'] ) . 'controller';
					$controller = new $controllerName( $this->registry, $mapping['auto_process'] );
					if( ! $mapping['auto_process'] )
					{
						$controller->$mapping['method']( $path );
					}
				}
			}
			if( ! $match )
			{
				require_once( FRAMEWORK_PATH . 'controllers/page/page.controller.php' );
				$controller = new Pagecontroller( $this->registry, true );
			}
		}
		 */
		
	}
	
	
	
}



?>