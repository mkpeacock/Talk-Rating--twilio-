<?php
/**
 * Ickle MVC URL Processor
 * @author Michael Peacock
 */
class Urlprocessor {
	
	/**
	 * "Bits" of the URL
	 * @var array
	 */
	private $urlBits = array();
	
	/**
	 * Requested URL path
	 * @var String
	 */
	private $urlPath;
	
	private $pathPrefix = "";

	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @return void
	 */
    public function __construct( IckleRegistry $registry )
    {
    	$this->registry = $registry;
    	$this->getURLData();
    }
    
    /**
     * Set the URL path
     * @param String the url path
     */
    public function setURLPath($path)
	{
		$this->urlPath = $path;
	}
	
	/**
	 * Gets data from the current URL
	 * @return void
	 */
	public function getURLData()
	{
		$urldata = ( isset( $_GET['page'] ) ) ? $_GET['page'] : '' ;
		$this->urlPath = $urldata;

		if( $urldata == '' )
		{
			$this->urlBits[] = '';
			$this->urlPath = '';
		}
		else
		{
			$data = explode( '/', $urldata );
			while( ! empty( $data ) && strlen( reset( $data ) ) === 0 ) 
			{
		    	array_shift( $data );
		    }
		    while( ! empty( $data ) && strlen( end( $data ) ) === 0 ) 
		    {
		        array_pop($data);
		    }
		    while( ! empty( $data ) && strlen( reset( $data ) ) === 0 ) 
		    {
		        array_shift( $data );
		    }
		    
		    while( !empty( $data ) && strlen( end( $data ) ) === 0 ) 
		    {
		        array_pop( $data );
		    }
			$this->urlBits = $data;
		}
		
	}
	
	/**
	 * Get the bits array from the URL
	 * @return array
	 */
	public function getURLBits()
	{
		return $this->urlBits;
	}
	
	/**
	 * Get a specific bit of a URL
	 * @param int $whichBit the bit of the URL
	 * @return String the bit
	 */
	public function getURLBit( $whichBit )
	{
		return ( isset( $this->urlBits[ $whichBit ] ) ) ? $this->urlBits[ $whichBit ]  : '' ;
	}
	
	/**
	 * Get the URL path
	 * @return String
	 */
	public function getURLPath()
	{
		return $this->urlPath;
	}
	
	/**
	 * Build a URL
	 * @param array $bits the bits to make up the array
	 * @param String $qs the query string
	 * @param Boolean $admin if the URL is an admin link or not
	 * @param bool $secure - should the URL be SSL?
	 * @return String the URL
	 */
	public function buildURL( $bits, $qs, $admin, $secure=false )
	{
		$admin = ( $admin == 1 ) ? $this->registry->getSetting('admin_folder') . '/' : '';
		$the_rest = $this->pathPrefix;
		foreach( $bits as $bit )
		{
			$the_rest .= $bit . '/';
		}
		$the_rest = ( $qs != '' ) ? $the_rest . '?&' .$qs : $the_rest;
		$url = ( $secure ) ? str_replace( 'http://', 'https://', $this->registry->getSetting('CORE_SITEURL') ) : $this->registry->getSetting('CORE_SITEURL');
		$url = $url . $admin . $the_rest;
		$url = rtrim( $url, '/' );
		return $url;
		
	}
	
	
	
}
?>