<?php
return array(
	'defaultNotFound' => "
			<h2 class='red'>This :resource:</h2>
			<ul>
				<li>Does not exist.</li>
				<li>No longer exists.</li>
				<li>Had never existed (even if you're dreaming).</li>
				<li>Will probably never exist.</li>
			</ul>
			<h2 class='orange'>You can:</h2>
			<ul>
				<li>Cry.</li>
				<li>Run fast and far.</li>
				<li>Lie on the ground, roll into a ball and moan pitifully.</li>
				<li>Shouting someone to pick you up, even if nobody will hear you.</li>
			</ul>
			<h2 class='green'>But the best is:</h2>
			<ul>
				<li>Click on the previous page button in your browser.</li>
				<li>Click <a href='".URL::route('home')."'>here</a> to return to home.</li>
			</ul>",
	'quoteNotFoundTitle' => 'Quote not found!',
	'userNotFoundTitle'  => 'User not found!',
	'tokenNotFoundTitle' => 'Token not found!',
	'quoteText'          => 'quote',
	'userText'           => 'user',
	'tokenText'          => 'token',
);