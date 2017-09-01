<?php

class CardsController {

	/**
	 * hold request vars
	 */
	protected $requestVars = array(
		"get" => array(),
		"post" => array()
	);

	/**
	 * constructor which get called with request vars
	 */
	public function __construct( $getVars, $postVars ) {
		$this->requestVars["get"] = $getVars;
		$this->requestVars["post"] = $postVars;
	}

	/**
	 * show the search form
	 */
	public function index() {
	}

	/**
	 * show the printable ticket list based on jql query
	 */
	public function tickets() {

		/**
		 * get and check jql query
		 */
		$jql = trim($this->requestVars["post"]["jql"]);
		if( strlen($jql) == 0 ) throw new Exception("Empty jql found.");

		/**
		 * create jira object and establish connection
		 */
		require_once(dirname(__FILE__)."/../Lib/Jira.php");
		$jira = new Jira($this->requestVars["post"]["path"]);
		$jira->auth($this->requestVars["post"]["username"], $this->requestVars["post"]["password"]);

		/**
		 * get tickets from jira
		 */
		$rawTickets = $jira->getIssuesByJql($jql);
		$tickets = array();
		foreach( $rawTickets->issues as $ticket ) {
			$tickets[] = $this->convertJiraIssueToArray($ticket);
		}

		/**
		 * add epic names to tickets, if wanted
		 */
		if( $this->requestVars["post"]["epic"] == "1" ) {
			$tickets = $this->addEpicNames($tickets, $jira);
		}

		/**
		 * return view vars
		 */
		return array(
			"tickets" => $tickets
		);
	}

	/**
	 * put the issues in a format we can work with,
	 * so limit to the most used values
	 */
	protected function convertJiraIssueToArray($ticket) {

		/**
		 * format the time to a readable value
		 */
		$time = intval($ticket->fields->timeoriginalestimate);
		if( $time > 0 ) $time = $time / 3600;
		$time = number_format($time, 1)." h";

		/**
		 * get avatar from jira
		 */
		$avatar = "";
		if( $ticket->fields->assignee ) {
			$av = (array) $ticket->fields->assignee->avatarUrls;
			$avatar = isset($av["48x48"]) ? $av["48x48"] : "";
		}

		/**
		 * collect the basic fields from jira
		 */
		$collectedTicket = array(
			"priority" => $ticket->fields->priority->name,
			"issuetype" => $ticket->fields->issuetype->name,
			"key" => $ticket->key,
			"summary" => $ticket->fields->summary,
			// "reporter" => $ticket->fields->reporter ? $ticket->fields->reporter->displayName : "n/a",
			// "assignee" => $ticket->fields->assignee ? $ticket->fields->assignee->displayName : "n/a",
			"parent" => isset($ticket->fields->parent) ? $ticket->fields->parent->key : "",
			"avatar" => $avatar,
			// "remaining_time" => $time,
			"story_points" => $ticket->fields->customfield_11292,
			"project_name" => $ticket->fields->project->name,
			"project_avatar_xsmall_url" => $ticket->fields->project->avatarUrls->{'16x16'}
		);

		if( $this->requestVars["post"]["reporter"] == "1" ) {
			$collectedTicket["reporter"] = $ticket->fields->reporter ? $ticket->fields->reporter->displayName : "n/a";
		}

		if( $this->requestVars["post"]["assignee"] == "1" ) {
			$collectedTicket["assignee"] = $ticket->fields->assignee ? $ticket->fields->assignee->displayName : "n/a";
		}

		if( $this->requestVars["post"]["remaining_time"] == "1" ) {
			$collectedTicket["remaining_time"] = $time;
		}

		/**
		 * add custom fields from Jira Agile (epic and rank)
		 */
		$customFields = array(
			"epicKey" => "customfield_11296",
			"rank" => "customfield_10004"
		);

		foreach( $customFields as $name => $key ) {
			if( property_exists($ticket->fields, $key ) ) {
				$collectedTicket[$name] = $ticket->fields->$key;
			}
		}

		/**
		 * return total collection
		 */
		return $collectedTicket;
	}

	/**
	 * add Agile-epic information to a ticket, since a ticket comes with the
	 * link to the epic, but we need to names, which we need to fetch from Jira seperately
	 */
	protected function addEpicNames($tickets, $jira) {

		/**
		 * collect all different keys
		 */
		$epicKeys = array();
		foreach( $tickets as $ticket ) {
			if(isset($ticket["epicKey"]) ) {
				$key = trim($ticket["epicKey"]);
				if(!empty($key)) $epicKeys[]= $key;
			}
		}
		$epicKeys = array_unique($epicKeys);
		if( count($epicKeys) == 0 ) return $tickets;


		/**
		 * get names pro jira and convert into nicer structure
		 */
		$rawEpics = $jira->getIssuesByJql("key IN (".implode(",", $epicKeys).")", "key,customfield_11296");
		$epics = array();
		foreach($rawEpics->issues as $epic) {
			$epics[$epic->key] = $epic->fields->customfield_11296;
		}

		/**
		  * modify tickets and add epic names
		 */
		for( $i=0; $i < count($tickets); $i++ ) {
			$key = trim($tickets[$i]["epicKey"]);
			$tickets[$i]["epic"] = !empty($key) ? $epics[$key] : "";
		}
		return $tickets;
	}
}
