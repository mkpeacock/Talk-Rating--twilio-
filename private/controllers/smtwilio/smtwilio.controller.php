<?php

class SMTwilioController {
	
	private $registry;
	private $twilioClient;
	private $url = 'http://url-to-this-application/';
	
	public function __construct( IckleRegistry $registry, $autoProcess=true )
	{
		$this->registry = $registry;
		
		// building on wamp server, doesnt play nicely with SSL, and the twilio SSL cert isn't available to download. so don't verify SSL
		require_once( FRAMEWORK_PATH . 'libraries/external/twilio/Twilio.php');
		$http = new Services_Twilio_TinyHttp('https://api.twilio.com', array('curlopts' => array(
		   		CURLOPT_SSL_VERIFYPEER => false
			)));
			
		$this->twilioClient = new Services_Twilio( 'TWILIO-ACCOUNT-SID ', 'TWILIO-AUTH-TOKEN', '2010-04-01', $http );
		
		// lazy: not doing any logic to divert control
		$this->menu();
	}
	
	private function menu()
	{
		$response = new Services_Twilio_Twiml();
		
		$talkID = 0;
		if( isset( $_GET['talk'] ) )
		{
			// save rating
			$talkID = intval( $_GET['talk'] );
			require_once( FRAMEWORK_PATH . 'models/talks/talk.php');
			$talk = new Talk( $this->registry, $talkID );
			if( $talk->isValid() )
			{
				$rating = (int) $_REQUEST['Digits'];
				$rating = ( $rating > 5 ) ? 5 : $rating;
				$rating = ( $rating < 0 ) ? 0 : $rating;
				$average = $talk->rate( $rating );
				// say thanks
				require_once( FRAMEWORK_PATH . 'models/talks/talks.php' );
				$talks = new Talks( $this->registry );
				$talk = $talks->getNextTalk( $talkID );
				if( empty( $talk ) )
				{
					// all done
					$response->say('Thanks for rating that talk ' . $rating . ' out of 5, the average is ' . $average . ' there are no more talks left to rate');
				}
				else
				{
					// another
					$gather = $response->gather( array( 'numDigits' => 1, 'action' => $this->url . '?&talk=' . $talk[0]->getID() ) );
					$gather->say('Thanks for rating that talk ' . $rating . ' out of 5, the average is ' . $average . '.');
					$gather->say('Please rate ' . $talk[0] . ' out of 5' );  
				}
			}
			else
			{
				// error
				$response->say('Sorry, it looks like you tried to rate a non-existant talk.');
			}			
		}
		else
		{
			$response->say('Welcome to the Super Mondays talk rating service.');
			require_once( FRAMEWORK_PATH . 'models/talks/talks.php' );
			$talks = new Talks( $this->registry );
			$talk = $talks->getNextTalk( $talkID );
			if( empty( $talk ) )
			{
				// all done
				$response->say('Sorry, there are no talks available for you to rate.');
			}
			else
			{
				// another
				$gather = $response->gather( array( 'numDigits' => 1, 'action' => $this->url . '?&talk=' . $talk[0]->getID() ) );
				$gather->say('Please rate ' . $talk[0] . ' out of 5' );  
			}
		}
		
		print $response;
		exit();
		
		
	}
	
	
}


?>