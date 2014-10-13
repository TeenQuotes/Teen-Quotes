<?php namespace TeenQuotes\Newsletters\Repositories;

use InvalidArgumentException;
use TeenQuotes\Newsletters\Models\Newsletter;
use TeenQuotes\Users\Models\User;

class DbNewsletterRepository implements NewsletterRepository {
	
	/**
	 * Tells if a user if subscribed to a newsletter type
	 * @param  TeenQuotes\Users\Models\User   $u    The given user
	 * @param  string $type The newsletter's type
	 * @return bool
	 */
	public function userIsSubscribedToNewsletterType(User $u, $type)
	{
		$this->guardType($type);

		return Newsletter::forUser($u)
			->type($type)
			->count() > 0;
	}

	/**
	 * Create a newsletter item for the given user
	 * @var TeenQuotes\Users\Models\User $user The user instance
	 * @var string $type The type of the newsletter : weekly|daily
	 * @throws InvalidArgumentException If the newsletter's type is wrong or if
	 * the user is already subscribed to the newsletter.
	 */
	public function createForUserAndType(User $user, $type)
	{
		$this->guardType($type);

		if ($this->userIsSubscribedToNewsletterType($user, $type))
			throw new InvalidArgumentException("The user is already subscribed to the newsletter of type ".$type.".");

		$newsletter          = new Newsletter;
		$newsletter->type    = $type;
		$newsletter->user_id = $user->id;
		$newsletter->save();
	}

	/**
	 * Delete a newsletter item for the given user
	 * @var TeenQuotes\Users\Models\User $user The user instance
	 * @var string $type The type of the newsletter : weekly|daily
	 */
	public function deleteForUserAndType(User $u, $type)
	{
		$this->guardType($type);

		return Newsletter::forUser($u)
			->type($type)
			->delete();
	}

	private function guardType($type)
	{
		if ( ! in_array($type, [Newsletter::WEEKLY, Newsletter::DAILY]))
			throw new InvalidArgumentException("Newsletter's type only accepts weekly or daily. ".$type." was given.");
	}
}