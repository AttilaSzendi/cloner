@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard - <em>Repositories</em></div>

                    <div class="card-body">
                        <repository-list></repository-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
