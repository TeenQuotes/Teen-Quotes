<?php
$colorQuote = $colors[$i];
$colorBorderQuote = Quote::adjustBrightness($colors[$i], -30);
$colorBubbleComments = Quote::adjustBrightness($colorQuote, 100);
?>
<div class="quote" style="background-color:<?= $colorQuote; ?>;border-bottom-color:<?= $colorBorderQuote; ?>">
	{{ $quote->content}}
	
	<div class="row quotes-info">
		<div class="col-md-2 col-xs-4">
			<a href="{{ URL::action('QuotesController@show', ['id' => $quote->id]) }}"><span class="badge transition" style="background:<?= $colorBorderQuote; ?>">#{{$quote->id}}</span></a>
			
			@if ($quote->has_comments)
				<a href="{{ URL::action('QuotesController@show', ['id' => $quote->id]) }}" class="comments-count"><i class="fa fa-comment nb-comments" style="color:<?= $colorBubbleComments ?>"></i>{{ $quote->total_comments }}</a>
			@endif
		</div>
		<div class="col-md-4 hidden-sm hidden-xs">
			<div class="fb-like" data-href="{{URL::route('quotes.show', array($quote->id), true)}}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
		</div>
		<div class="col-md-2 col-xs-2">
			@if ($quote->is_favorite_for_current_user)
				<span class="badge">favorite</span>
			@else
				<span class="badge">no favorite</span>
			@endif
		</div>
		<div class="col-md-4 col-xs-6">
			<a href="{{ URL::action('UsersController@show', ['id' => $quote->user->login]) }}" class="transition link-author-profile">{{$quote->user->login}}</a>
		</div>
	</div>
</div>