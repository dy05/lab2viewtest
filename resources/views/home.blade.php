@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <div class="mt-4">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Browser</th>
                                    <th>Ip</th>
                                    <th>DateTime</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse(Auth::user()->logs as $log)
                                <tr>
                                    <td>
                                        {{ $log->type }}
                                    </td>
                                    <td>
                                        {{ $log->metadata['ip'] ?? '' }}
                                    </td>
                                    <td>
                                        {{ $log->metadata['browser'] ?? '' }}
                                    </td>
                                    <td>
                                        {{ $log->created_at }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        There's no log
                                    </td>
                                </tr>
                            @endforelse
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
