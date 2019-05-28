@extends('layouts.layout')

@section('content')
<div class="container">

    <h1>All the Nerds</h1>

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <td>ID</td>
                <td>Quiz Test</td>
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
        @foreach($quizs as $key => $value)
            <tr>
                <td>{{ $value->id }}</td>
                <td>{{ $value->test_name }}</td>

                <!-- we will also add show, edit, and delete buttons -->
                <td>

                    <!-- delete the nerd (uses the destroy method DESTROY /nerds/{id} -->
                    <!-- we will add this later since its a little more complicated than the other two buttons -->

                    <!-- show the nerd (uses the show method found at GET /nerds/{id} -->
                    <a class="btn btn-small btn-success" href="{{ Route('quizs.edit', ['id' => $value->id]) }}">Show this Nerd</a>

                    <!-- edit this nerd (uses the edit method found at GET /nerds/{id}/edit -->
                    <a class="btn btn-small btn-info" href="{{ URL::to('quizs/' . $value->id . '/edit') }}">Edit this Nerd</a>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    </div>
@endsection
