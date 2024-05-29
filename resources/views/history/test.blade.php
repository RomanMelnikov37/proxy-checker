@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>История проверок</h1>
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
                <th>Дата проверки</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($checks as $check)
                <tr>
                    <td>{{ $check->ip }}</td>
                    <td>{{ $check->port }}</td>
                    <td>{{ $check->type }}</td>
                    <td>{{ $check->country }}</td>
                    <td>{{ $check->city }}</td>
                    <td>{{ $check->is_working ? 'Работает' : 'Не работает' }}</td>
                    <td>{{ $check->speed ? $check->speed . ' ms' : 'N/A' }}</td>
                    <td>{{ $check->external_ip }}</td>
                    <td>{{ $check->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $checks->links() }}
    </div>
@endsection