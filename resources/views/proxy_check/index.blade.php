@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Проверка прокси</h1>
        <form action="/check" method="POST">
            @csrf
            <div class="form-group">
                <label for="proxies">Введите прокси (ip:port, каждый с новой строки):</label>
                <textarea class="form-control" id="proxies" name="proxies" rows="10"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Проверить</button>
        </form>
    </div>
@endsection