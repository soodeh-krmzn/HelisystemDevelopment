@extends('parts.zero-master')
@section('title', 'میز کار')
@section('styles')
@endsection
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <b>ورود به سایت</b>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">فرم زیر را تکمیل کنید و ورود بزنید</p>

                <form action="" method="POST">
                    @csrf
                    @if ($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="row">
                                <div class="col-md-12 alert alert-danger">
                                    {{ $error }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="form-group">
                        <label>نام کاربری <span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <input type="text" name="username" class="form-control" placeholder="نام کاربری..." value="{{ old('username') }}">
                            <div class="input-group-append">
                                <span class="fa fa-user input-group-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>رمز عبور <span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <input type="password" name="password" class="form-control" placeholder="رمز عبور...">
                            <div class="input-group-append">
                                <span class="fa fa-lock input-group-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-block btn-flat">ورود</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
