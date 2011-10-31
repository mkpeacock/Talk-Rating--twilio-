<?php
/**
 * IckleMVC Abstract Database Class
 * 
 * @author Michael Peacock
 * @copyright Ickle MVC Project
 */
abstract class Database {
	
	/**
	 * Allows multiple database connections
	 * each connection is stored as an element in the array, and the active connection is maintained in a variable (see below)
	 */
	protected $connections = array();
	
	/**
	 * Tells the DB object which connection to use
	 * setActiveConnection($id) allows us to change this
	 */
	protected $activeConnection = 0;
	
	/**
	 * Queries which have been executed and the results cached for later, primarily for use within the template engine
	 */
	protected $queryCache = array();
	
	/**
	 * Data which has been prepared and then cached for later usage, primarily within the template engine
	 */
	protected $dataCache = array();
	
	/**
	 * Number of queries made during execution process
	 */
	protected $queryCounter = 0;
	
	/**
	 * Record of the last query
	 */
	protected $previouslyExecuted;
	
	/**
	 * Reference to the registry object
	 */
	protected $registry;
	
	abstract public function newConnection( $host, $user, $password, $database );
	abstract public function executeQuery( $sql );
	abstract public function getNumRows();
	abstract public function getRows();
	abstract public function insertRecord( $table, $record );
	abstract public function getLastInsertID();
	abstract public function insertRecords( $table, $records );
	abstract public function updateRecords( $table, $record, $condition );
	abstract public function updateRecordsMulti( $table, $records, $conditions );
	abstract public function deleteRecords( $table, $condition, $limit );
	abstract public function getNumAffectedRows();
	abstract public function cacheQuery( $sql );
	abstract public function getNumRowsFromCache( $cache_id );
	abstract public function getResultsFromCache( $cache_id );
    
	
	/**
     * Store some data in a cache for later
     * @param array the data
     * @return int the pointed to the array in the data cache
     */
    public function cacheData( $data )
    {
    	$this->dataCache[] = $data;
    	return count( $this->dataCache )-1;
    }
    
    /**
     * Get data from the data cache
     * @param int data cache pointed
     * @return array the data
     */
    public function dataFromCache( $cache_id )
    {
    	return $this->dataCache[$cache_id];
    }
    
    public function getActiveConnection()
    {
    	return $this->activeConnection;
    }
    
    public function setActiveConnection( $connection )
    {
    	$this->activeConnection = $connection;
    }
	
	
	
}

?>