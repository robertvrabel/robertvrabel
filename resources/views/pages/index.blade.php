@extends('app')

@section('content')
    <div class="row">
        <div class="small-12 medium-12 large-6 columns section-group">
            <h2>My Recent Reviews</h2>

            @foreach ($user_activity as $beer)
                <div class="row section-item @if ($beer == end($user_activity)) last @endif">
                    <div class="small-3 medium-2 large-2 columns">
                        <a href="{{ $beer['beer']['url'] }}" target="_blank"><img src="{{ $beer['beer']['beer_label'] }}" /></a>
                    </div>

                    <div class="small-9 medium-10 large-10 columns">
                        <div><span class="rating">{{ $beer['rating_score'] }}</span> <a href="{{ $beer['beer']['url'] }}" target="_blank">{{ $beer['beer']['beer_name'] }}</a></div>
                        <div class="brewery">{{ $beer['brewery']['brewery_name'] }}</div>
                        <div class="date">{{ $beer['created_at'] }}</div>
                    </div>
                </div>
            @endforeach

            <div class="more"><a href="http://untappd.com/user/{{ $untappd_username }}" target="_blank">More Checkins</a></div>
        </div>

        <div class="small-12 medium-12 large-6 columns section-group">
            <h2>{{ $brewery_activity[0]['brewery']['brewery_name'] }} Activity Feed</h2>

            @foreach ($brewery_activity as $beer)
                <div class="row section-item @if ($beer == end($brewery_activity)) last @endif">
                    <div class="small-3 medium-2 large-2 columns">
                        <a href="{{ $beer['beer']['url'] }}" target="_blank"><img src="{{ $beer['beer']['beer_label'] }}" /></a>
                    </div>

                    <div class="small-9 medium-10 large-10 columns">
                        <div><span class="rating">{{ $beer['rating_score'] }}</span> <a href="{{ $beer['beer']['url'] }}" target="_blank">{{ $beer['beer']['beer_name'] }}</a></div>
                        <div class="brewery"><a href="http://untappd.com/user/{{ $beer['user']['user_name'] }}/" target="_blank">{{ $beer['user']['user_name'] }}</a></div>
                        <div class="date">{{ $beer['created_at'] }}</div>
                    </div>
                </div>
            @endforeach

            <div class="more"><a href="http://untappd.com/{{ getenv('UNTAPPD_BREWERY') }}" target="_blank">More Checkins</a></div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 medium-12 large-6 columns section-group">
            <h2>{{ $posts['blog']['title'] }}</h2>

            @foreach ($posts['posts'] as $post)
                <div class="row section-item @if ($post == end($posts['posts'])) last @endif">
                    <div class="small-12 medium-6 columns">
                        @if($post['image_url'] != '')
                            <a href="{{ $post['url'] }}"><img src="{{ $post['image_url'] }}"></a>
                        @endif
                    </div>

                    <div class="small-12 medium-6 columns">
                        {!! $post['description'] !!}
                    </div>
                </div>
            @endforeach

            <div class="more"><a href="{{ $posts['blog']['url'] }}" target="_blank">More Posts</a></div>
        </div>

        <div class="small-12 medium-12 large-6 columns section-group">
            <h2>Nathan Quotes</h2>

            @foreach ($posts_quotes['posts'] as $post)
                <div class="row section-item @if ($post == end($posts_quotes['posts'])) last @endif">
                    <div class="small-12 columns">
                        <h4>{!! $post['title'] !!}</h4>

                        {!! $post['description'] !!}
                    </div>
                </div>
            @endforeach

            <div class="more"><a href="{{ $posts_quotes['blog']['url'] }}" target="_blank">More Posts</a></div>
        </div>
    </div>
@endsection