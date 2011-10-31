<?php
/**
 * Talk S Model
 * @author Michael Peacock
 */
class Talks{
	
	/**
	 * Registry object
	 * @var IckleRegistry
	 */
	private $registry;
	
	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @return void
	 */
	public function __construct( IckleRegistry $registry )
	{
		$this->registry = $registry;
	}
	
	/**
	 * Get the next talk in the list
	 * @param int $start the talkID to get the next one from
	 * @return array
	 */
	public function getNextTalk( $start=0 )
	{
		$sql = "SELECT * FROM talks WHERE ID>{$start} ORDER BY ID ASC LIMIT 1";
		return $this->buildFromSQL( $sql );
	}
	
	/**
	 * Build a collection of talk objects from an SQL statement
	 * @param String $sql
	 * @return array - an array of Talk objects
	 */
	private function buildFromSQL( $sql )
	{
		$this->registry->getObject('db')->executeQuery( $sql );
		$tor = array();
		if( $this->registry->getObject('db')->getNumRows() == 1 )
		{
			require_once( FRAMEWORK_PATH . 'models/talks/talk.php' );
			while( $row = $this->registry->getObject('db')->getRows() )
			{
				$talk = new Talk( $this->registry, 0 );
				$talk->setName( $row['name'] );
				$talk->setPresenter( $row['presenter'] );
				$talk->setID( $row['ID'] );
				$tor[] = $talk;
			}
		}
		return $tor;
	}
	
	
}


?>