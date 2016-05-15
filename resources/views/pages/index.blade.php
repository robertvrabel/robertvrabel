@extends('app')

@section('content')
    <div class="row">
        <div class="small-12 medium-12 large-6 columns section-group">
            <h2>Recent Beer Reviews</h2>

            @foreach ($user_activity as $beer)
                <div class="row beer-item">
                    <div class="small-3 medium-2 large-2 columns">
                        <a href="{{ $beer['beer']['url'] }}" target="_blank"><img src="{{ $beer['beer']['beer_label'] }}" /></a>
                    </div>

                    <div class="small-9 medium-10 large-10 column">
                        <div><span class="rating">{{ $beer['rating_score'] }}</span> <a href="{{ $beer['beer']['url'] }}" target="_blank">{{ $beer['beer']['beer_name'] }}</a></div>
                        <div class="brewery">{{ $beer['brewery']['brewery_name'] }}</div>
                        <div class="date">{{ $beer['created_at'] }}</div>
                    </div>
                </div>
            @endforeach

            <div class="more"><a href="http://untappd.com/user/{{ $untappd_username }}" target="_blank">More Reviews</a></div>
        </div>

        <div class="small-12 medium-12 large-6 columns section-group">
            <h2>{{ $brewery_activity[0]['brewery']['brewery_name'] }}</h2>

            @foreach ($brewery_activity as $beer)
                <div class="row beer-item">
                    <div class="small-3 medium-2 large-2 columns">
                        <a href="{{ $beer['beer']['url'] }}" target="_blank"><img src="{{ $beer['beer']['beer_label'] }}" /></a>
                    </div>

                    <div class="small-9 medium-10 large-10 column">
                        <div><span class="rating">{{ $beer['rating_score'] }}</span> <a href="{{ $beer['beer']['url'] }}" target="_blank">{{ $beer['beer']['beer_name'] }}</a></div>
                        <div class="brewery"><a href="http://untappd.com/user/{{ $beer['user']['user_name'] }}/" target="_blank">{{ $beer['user']['user_name'] }}</a></div>
                        <div class="date">{{ $beer['created_at'] }}</div>
                    </div>
                </div>
            @endforeach

            <div class="more"><a href="http://untappd.com/{{ getenv('UNTAPPD_BREWERY') }}" target="_blank">More Checkins</a></div>
        </div>
    </div>
@endsection