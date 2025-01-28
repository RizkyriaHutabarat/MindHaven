@extends('layouts.app') <!-- Sesuaikan dengan layout Anda -->

@section('content')
<div class="container">
    <h2>Lupa Password</h2>

    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('psikolog.forgot_password.post') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Kirim Link Reset Password</button>
    </form>
    <a href="{{ route('psikolog.login') }}">Kembali ke Login</a>
</div>
@endsection
