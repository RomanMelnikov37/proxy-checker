@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>История проверок</h1>
        <ul>
            @foreach($checkResults as $result)
                <li>
                    <a href="{{ route('history.show', $result->id) }}">
                        Проверка от {{ $result->checked_at }} - {{ $result->total_proxies }} прокси, {{ $result->working_proxies }} рабочих, время {{ $result->duration }} сек
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection