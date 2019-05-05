@extends('layouts.layout')

@section('content')

<div class="container introduce-bg2">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
              <div class="card-header">{{ $user->name . __('Personal Information') }}  </div>
                <div class="card-body">

                    <div class="row text-left">
                      <h2>{{ $user->name }}</h2>
                    </div>

                    <div class="row text-left">

                        <p>
                            <strong>{{__('School')}}:</strong> {{$user->school}}<br>
                            <strong>{{__('Student ID')}}:</strong> {{$user->student_id}}<br>
                            <strong>{{__('E-Mail')}}:</strong> {{ $user->email }}<br>
                            <strong>{{__('Admin')}}:</strong> {!! $user->is_admin ? '<i style="color:green" class="fas fa-check"></i>' : '<i style="color:red" class="fas fa-times"></i>' !!}
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <a class="btn btn-danger" href="{{ URL::previous() }}">
                              {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
