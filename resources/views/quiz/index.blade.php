@extends('layouts.layout')

@section('content')
<div class="container">

    <h1>題庫管理系統</h1>

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif


    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <td>ID</td>
                <td>測驗名稱</td>
                <td>測驗時間</td>
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
        @foreach($quizs as $key => $value)
            <tr>
                <td>{{ $value->id }}</td>
                <td>{{ $value->test_name }}</td>
                <td>{{ $value->test_time }}</td>

                <td>
                    <a class="btn btn-small btn-success" href="{{ Route('quizs.edit', ['id' => $value->id]) }}"><i class="fas fa-edit"></i>題目</a>
                    <form style="display: inline-block;" action="{{ Route('quizs.destroy', ['id' => $value->id]) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                    </form>
                    {{-- <a class="btn btn-small btn-danger" href="{{ Route('quizs.destroy', ['id' => $value->id]) }}">刪除題庫</a> --}}
                    <a class="btn btn-small btn-warning" href="{{ Route('quizs.editquiz', ['id' => $value->id]) }}"><i class="fas fa-edit"></i>題庫</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a class="btn btn-small btn-success" href="{{ Route('quizs.create') }}"><i class="fas fa-plus-circle"></i>題庫</a>
    </div>
@endsection
