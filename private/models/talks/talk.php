<?php
/**
 * Talk model
 * @author Michael Peacock
 */
class Talk{
	
	private $ID;
	private $name;
	private $presenter;
	
	/**
	 * Registry object
	 * @var IckleRegistry
	 */
	private $registry;
	
	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @param int $ID
	 * @return void
	 */
	public function __construct( IckleRegistry $registry, $ID=0 )
	{
		$this->registry = $registry;
		if( $ID > 0 )
		{
			$sql = "SELECT ID, name, presenter FROM talks WHERE ID=" . $ID;
			$this->registry->getObject('db')->executeQuery( $sql );
			if( $this->registry->getObject('db')->getNumRows() == 1 )
			{
				$this->valid = true;
				$row = $this->registry->getObject('db')->getRows();
				$this->ID = $ID;
				$this->name = $row['name'];
				$this->presenter = $row['presenter'];
			}
		}
		
	}
	
	/**
	 * Is this a valid talk?
	 * @return bool
	 */
	public function isValid()
	{
		return $this->valid;
	}
	
	/**
	 * Setter: ID
	 * @param int $ID
	 * @return void
	 */
	public function setID( $ID )
	{
		$this->ID = $ID;
	}
	
	/**
	 * Setter: talk
	 * @param String $talk
	 * @return void
	 */
	public function setName( $talk )
	{
		$this->name = $talk;
	}
	
	/**
	 * Setter: presenter
	 * @param String $presenter
	 * @return void
	 */
	public function setPresenter( $presenter )
	{
		$this->presenter = $presenter;
	}
	
	/**
	 * Getter: ID
	 * @return int
	 */
	public function getID()
	{
	 	return $this->ID;
	}
	
	/**
	 * Getter: talk
	 * @return String
	 */
	public function getTalk()
	{
	 	return $this->talk;
	}
	
	/**
	 * Getter: presenter
	 * @return String
	 */
	public function getPresenter()
	{
	 	return $this->presenter;
	}
	
	/**
	 * Convert the talk to a string
	 * @return String
	 */
	public function __toString()
	{
		return $this->name . ' presented by ' . $this->presenter;
	}
	
	/**
	 * Rate the talk
	 * @param int $rating
	 * @return mixed (int|string)
	 */
	public function rate( $rating )
	{
		if( $rating > 5 )
		{
			$rating = 5;
		}
		elseif( $rating < 0 )
		{
			$rating = 0;
		}
		
		$this->registry->getObject('db')->insertRecords( 'ratings', array( 'talk' => $this->ID, 'rating' => $rating ) );
		$sql = "SELECT AVG(rating) as average FROM ratings WHERE talk=" . $this->ID . " LIMIT 1";
		$average = 'No average';
		$this->registry->getObject('db')->executeQuery( $sql );
		if( $this->registry->getObject('db')->getNumRows() > 0 )
		{
			$row = $this->registry->getObject('db')->getRows();
			$average = round( $row['average'], 0 );
		}
		
		return $average;
		
	}
	
	
	
}

?>