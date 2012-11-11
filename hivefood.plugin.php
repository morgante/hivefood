<?php

class HiveFood extends Plugin
{
	
	public function action_update_check()
	{
		Update::add( $this->info->name, 'd0d1105d-a110-4e5a-a755-3f3b249a42f8', $this->info->version );
	}


	/**
	 * Create rewrite rule
	 **/
	public function action_init()
	{
		$this->add_rule('"hivefood"', 'hivefood_home');
	}


	/**
	 * Handle register_page action
	 **/
	public function action_plugin_act_hivefood_home($handler)
	{
		$hiveminder = new Hiveminder( 'ab21445136c5c6b2ae8df1fbb68be04a' );
		
		$task = new Task;
		$task->title = 'Read blablabla';
		$task->add_braindump( 'due today' );
		$task->add_braindump( 'life school' );
		$task->add_braindump( 'priority: high' );
		
		$hiveminder->create_task( $task );
		
		$handler->theme->display('hivefood_home');
	}
	
}

class Hiveminder
{
	
	private $key;
	
	private $urls = array(
		'createTask' => 'http://hiveminder.com/=/model/Task.xml'
	);
	
	public function __construct( $cookie ) {
		
		$this->key = $cookie;
		
	}
	
	public function create_task( $task ) {
		
		$params = array(
			'title' => $task->title,
		);
		
		Utils::debug( $this->request( $this->urls['createTask'], $params, 'POST' ) );
		
	}
	
	private function request( $url, $params, $method = 'GET') {
		
		$request = new RemoteRequest( $url, $method );
		
		// $headers[] = 'Content-Type: multipart/form-data';
		$headers[] = array('Cookie' => 'JIFTY_SID_HIVEMINDER=' . $this->key);
		
		$request->add_headers( $headers );
		
		$request->set_postdata( $params );
		
		if( $request->execute() ) {
			return $request;
			// return $request->executed();
			// return $request->get_response_body();
		}
		
	}
	
}

class Task
{

	public function __construct() {
		
		$this->title = '';
		
	}
	
	public function add_braindump( $braindump ) {
		
		$this->title .= '[' . $braindump . ']';
		
	}
	
}

?>