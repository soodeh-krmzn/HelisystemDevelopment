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

                <form action="{{ route('register.store') }}" method="POST">
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
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>نام <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="نام...">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>نام خانوادگی <span class="text-danger">*</span></label>
                                <input type="text" name="family" class="form-control" value="{{ old('family') }}" placeholder="نام خانوادگی...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>موبایل <span class="text-danger">*</span></label>
                        <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}" placeholder="موبایل...">
                    </div>
                    <div class="form-group">
                        <label>رمز عبور <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="رمز عبور...">
                    </div>
                    <div class="form-group">
                        <label>تکرار رمز عبور <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="تکرار رمز عبور...">
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-block btn-flat">ثبت نام</button>
                        </div>
                    </div>
                </form>

                <hr>

                <p class="mb-0">
                    حساب کاربری دارید؟ <a href="{{ route('login') }}" class="text-center">ورود</a>
                </p>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
