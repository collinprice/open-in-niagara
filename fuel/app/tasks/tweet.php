<?php

namespace Fuel\Tasks;

class Tweet {

	public static function run($date = null) {
		date_default_timezone_set('US/Eastern');
		$time = ($date == null) ? time() : strtotime($date);
		$date = getdate($time);
		
		if ($date['year'] == 1969) {
			return \Cli::color("Invalid date.", 'red');
		}

		$day_of_week = $date['wday'];
		$month = $date['mon'];
		$day = $date['mday'];
		$today = $date['year'] . '-' . $date['mon'] . '-' . $date['mday'];

		$sql = "SELECT * FROM hours_of_operation
				INNER JOIN venue ON hours_of_operation.venue_id = venue.id
				WHERE day_of_week = $day_of_week
				AND (DAY(valid_from) <= $day AND MONTH(valid_from) <= $month)
				AND (DAY(valid_to) >= $day AND MONTH(valid_to) >= $month)";

		$result = \DB::query($sql)->execute();

		foreach ($result as $row) {

			if (isset($row['open_message'])) {
				$default_open_message = $row['open_message'];
			} else {
				$default_open_message = $row['name'] . " is open for the day!";
			}

			if (isset($row['website'])) {
				$default_open_message .= ' ' . $row['website'];
			}

			if (isset($row['close_message'])) {
				$default_closed_message = $row['close_message'];
			} else {
				$default_closed_message = $row['name'] . " is now closed for the day :(";
			}

			// Check if venue is open
			if (isset($row['open_time'])) {

				$opentime = strtotime($today . ' ' . $row['open_time']);
				if ($time > $opentime && $time < strtotime('+10 minutes', $opentime)) {
					if (isset($row['latitude']) && isset($row['longitude'])) {
						Tweet::tweet($default_open_message, $row['latitude'], $row['longitude']);
					} else {
						Tweet::tweet($default_open_message);
					}
				}
			}

			// Check if venue is closed
			if (isset($row['close_time'])) {

				$opentime = strtotime($today . ' ' . $row['close_time']);
				if ($time > $opentime && $time < strtotime('+10 minutes', $opentime)) {
					if (isset($row['latitude']) && isset($row['longitude'])) {
						Tweet::tweet($default_closed_message, $row['latitude'], $row['longitude']);
					} else {
						Tweet::tweet($default_closed_message);
					}
				}
			}
		}
	}

	private static function tweet($message, $lat = null, $lon = null) {
		// Debug
		echo $message . "\n"; return;

		$settings = array(
			'consumer_key' => "sPGrK11aLEKXIFg6oaIIQ",
			'consumer_secret' => "CFzJA1SFTog4LdFRNflxo6trESZC0mIFaPL3l7qCws",
			'oauth_access_token' => "1213447898-l9WJewOPpY8QcaJfAPdKIH1UD1a8HKlepaghqyI",
			'oauth_access_token_secret' => "F5ekr8n3groxY6DlqBPDL8cuAdgid0J63hPXnSGezg",
		);

		$twitter = new \TwitterAPIExchange($settings);
		$url = "https://api.twitter.com/1.1/statuses/update.json";

		$postFields = array(
			'status' => $message,
		);

		if ($lat != null && $lon != null) {
			$postFields['lat'] = $lat;
			$postFields['long'] = $lon;
		}

		$result = json_decode($twitter->buildOauth($url, 'POST')
					 ->setPostfields($postFields)
					 ->performRequest(), true);
	}
}