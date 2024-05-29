@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Результаты проверки</h1>
        <p>Общее количество проверенных прокси: {{ $total }}</p>
        <p>Количество рабочих прокси: {{ $working }}</p>
        <table class="table">
            <thead>
            <tr>
                <th>IP</th>
                <th>Порт</th>
                <th>Тип</th>
                <th>Страна</th>
                <th>Город</th>
                <th>Статус</th>
                <th>Скорость</th>
                <th>Внешний IP</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($results as $result)
                <tr>
                    <td>{{ $result['ip'] }}</td>
                    <td>{{ $result['port'] }}</td>
                    <td>{{ $result['type'] }}</td>
                    <td>{{ $result['country'] }}</td>
                    <td>{{ $result['city'] }}</td>
                    <td>{{ $result['is_working'] ? 'Работает' : 'Не работает' }}</td>
                    <td>{{ $result['speed'] ? $result['speed'] . ' ms' : 'N/A' }}</td>
                    <td>{{ $result['external_ip'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection