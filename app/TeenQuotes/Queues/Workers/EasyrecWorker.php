<?php namespace TeenQuotes\Queues\Workers;

use Easyrec, URL;

class EasyrecWorker {

	/**
	 * Register the view of a quote
	 * @param \Illuminate\Queue\Jobs\SyncJob $job
	 * @param array $data Required keys: quote_id and user_id.
	 */
	public function viewQuote($job, $data)
	{
		Easyrec::view($data['quote_id'],
			"Quote ".$data['quote_id'],
			URL::route("quotes.show", $data['quote_id'], false),
			$data['user_id'],
			null, // No image URL
			null, // Current timestamp
			"QUOTE"
		);
	}

	/**
	 * Register the view of a user profile
	 * @param \Illuminate\Queue\Jobs\SyncJob $job
	 * @param array $data Required keys: viewer_user_id, user_login and user_id.
	 */
	public function viewUserProfile($job, $data)
	{
		Easyrec::view($data['user_id'],
			"User ".$data['user_id'],
			URL::route("users.show", $data['user_login'], false),
			$data['viewer_user_id'],
			null, // No image URL
			null, // Current timestamp
			"USERPROFILE"
		);
	}

	/**
	 * Add a quote to the favorites
	 * @param \Illuminate\Queue\Jobs\SyncJob $job  [description]
	 * @param array $data Required keys: quote_id, user_id
	 */
	public function favoriteAQuote($job, $data)
	{
		Easyrec::sendAction($data['quote_id'],
			"Quote ".$data['quote_id'],
			URL::route("quotes.show", $data['quote_id'], false),
			"FAVORITE",
			null, // Action value
			$data['user_id'],
			null, // No image URL
			null, // Current timestamp
			"QUOTE"
		);
	}

	/**
	 * Remove a quote from the favorites
	 * @param \Illuminate\Queue\Jobs\SyncJob $job  [description]
	 * @param array $data Required keys: quote_id, user_id
	 */
	public function unfavoriteAQuote($job, $data)
	{
		Easyrec::sendAction($data['quote_id'],
			"Quote ".$data['quote_id'],
			URL::route("quotes.show", $data['quote_id'], false),
			"UNFAVORITE",
			null, // Action value
			$data['user_id'],
			null, // No image URL
			null, // Current timestamp
			"QUOTE"
		);
	}
}