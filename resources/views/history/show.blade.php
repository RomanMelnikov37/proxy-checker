@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Детали проверки от {{ $proxyCheckResult->created_at }}</h1>
        <p>Время проверки: {{ $proxyCheckResult->duration }} сек</p>
        <p>Количество прокси: {{ $proxyCheckResult->total_proxies }}</p>
        <p>Количество живых прокси: {{ $proxyCheckResult->working_proxies }}</p>

        <h2>Результаты:</h2>
        <table class="table">
            <thead>
            <tr>
                <th>IP</th>
                <th>Port</th>
                <th>Тип</th>
                <th>Страна</th>
                <th>Город</th>
                <th>Статус</th>
                <th>Скорость</th>
                <th>Внешний IP</th>
            </tr>
            </thead>
            <tbody>
            @foreach($proxyChecks as $check)
                <tr>
                    <td>{{ $check->ip }}</td>
                    <td>{{ $check->port }}</td>
                    <td>{{ $check->type }}</td>
                    <td>{{ $check->country }}</td>
                    <td>{{ $check->city }}</td>
                    <td>{{ $check->is_working ? 'Работает' : 'Не работает' }}</td>
                    <td>{{ $check->speed }}</td>
                    <td>{{ $check->external_ip }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection