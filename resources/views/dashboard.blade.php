@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard Cleaning Service</h1>
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3>Karyawan</h3>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-primary">Lihat Data</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection