<?php namespace TeenQuotes\Quotes\Models\Relations;

trait FavoriteQuoteTrait {

	public function user()
	{
		return $this->belongsTo('TeenQuotes\Users\Models\User');
	}

	public function quote()
	{
		return $this->belongsTo('TeenQuotes\Quotes\Models\Quote');
	}
}