<?php

class Controller_Home extends Controller_Template {

	public $template = 'master';

	public function action_index() {

		$today = $this->getEvents();
		$today = $today['events'];

		$upcoming = array(
			$this->getEvents(strtotime('+1 day', time())),
			$this->getEvents(strtotime('+2 day', time())),
			$this->getEvents(strtotime('+3 day', time())),
		);

		$venues = DB::select(array('name', 'title'), 'latitude', 'longitude')->from('venue')->execute();

		$data = array(
			'waypoints' => $venues->as_array(),
			'today' => $today,
			'upcoming' => $upcoming,
		);

        $this->template->title = 'Open In Niagara';
        $this->template->content = View::forge('home/index', $data, false);
	}

	private function getEvents($time = null) {
		$time = ($time == null) ? time() : $time;

		$date = getdate($time);
		$day_of_week = $date['wday'];
		$month = $date['mon'];
		$day = $date['mday'];

		$sql = "SELECT * FROM hours_of_operation
				INNER JOIN venue ON hours_of_operation.venue_id = venue.id
				WHERE day_of_week = $day_of_week
				AND (DAY(valid_from) <= $day AND MONTH(valid_from) <= $month)
				AND (DAY(valid_to) >= $day AND MONTH(valid_to) >= $month)
				ORDER BY `open_time`";

		return array(
			'day' => $date['weekday'],
			'events' => \DB::query($sql)->execute()->as_array(),
		);
	}
}