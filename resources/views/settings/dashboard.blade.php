@extends('settings.layout')

@section('settings_title')
    <div class="row row--bottom row--separate mb-3">
        <h2>{{ __('general.dashboard') }}</h2>
        <a href="{{ route('widgets.create') }}" class="button">Create Widget</a>
    </div>
@endsection

@section('settings_body_formless')
    <div class="box">
        @foreach ($widgets as $widget)
            <div class="box__section row">
                <div class="row__column">{{ ucfirst($widget->type) }}</div>
                <div class="row__column row">
                    <form method="POST" action="{{ route('widgets.up', ['widget' => $widget->id]) }}">
                        {{ csrf_field() }}
                        <button class="button link">
                            <i class="fas fa-arrow-alt-up"></i>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('widgets.down', ['widget' => $widget->id]) }}" class="ml-2">
                        {{ csrf_field() }}
                        <button class="button link">
                            <i class="fas fa-arrow-alt-down"></i>
                        </button>
                    </form>
                </div>
                <div class="row__column row__column--compact">
                    <form method="POST" action="{{ route('widgets.destroy', ['widget' => $widget->id]) }}">
                        {{ csrf_field() }}
                        <button class="button link">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
