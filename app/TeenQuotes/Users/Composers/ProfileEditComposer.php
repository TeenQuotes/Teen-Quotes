<?php namespace TeenQuotes\Users\Composers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use TeenQuotes\Tools\Composers\AbstractDeepLinksComposer;

class ProfileEditComposer extends AbstractDeepLinksComposer {

	public function compose($view)
	{
		$data = $view->getData();
		$user = $data['user']; 
		$login = $user->login;

		// For deep links
		$view->with('deepLinksArray', $this->createDeepLinks('users/'.$login.'/edit'));
		$view->with('weeklyNewsletter', $user->isSubscribedToNewsletter('weekly'));
		$view->with('dailyNewsletter', $user->isSubscribedToNewsletter('daily'));
		$view->with('colorsAvailable', $this->createSelectColorsData());
	}

	/**
	 * Create an array like ['blue' => 'Blue', 'red' => 'Red']
	 * @return array
	 */
	private function createSelectColorsData()
	{
		// Create an array like
		// ['blue' => 'Blue', 'red' => 'Red']
		$colorsInConf = Config::get('app.users.colorsAvailableQuotesPublished');
		$func = function ($colorName) {
			return Lang::get('colors.'.$colorName);
		};
		$colorsAvailable = array_combine($colorsInConf, array_map($func, $colorsInConf));

		return $colorsAvailable;
	}
}