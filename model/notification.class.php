<?php

require_once __DIR__.'/../app/database/db.class.php';

class Notification
{
	protected $notification_id, $from_type, $from_id, $to_type, $to_id, $notification_title, $notification_text, $is_read, $created_at;

	function __construct( $notification_id, $from_type, $from_id, $to_type, $to_id, $notification_title, $notification_text, $is_read = 0, $created_at)
	{
		$this->notification_id = $notification_id;
		$this->from_type = $from_type;
		$this->from_id = $from_id;
        $this->to_type = $to_type;
		$this->to_id = $to_id;
		$this->notification_title = $notification_title;
		$this->notification_text = $notification_text;
        $this->is_read = $is_read;
        $this->created_at = $created_at;

	}

	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }
	

}

?>
