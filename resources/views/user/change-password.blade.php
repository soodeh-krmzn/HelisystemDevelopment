@extends('parts.master')
@section('title', 'تغییر رمز')
@section('styles')
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">تغییر رمز {{ $user->getFullName() }}</h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <a href="{{ route('user.index') }}" class="btn btn-success btn-sm">بازگشت به لیست کاربران</a>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('user.updatePassword') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="row">
                                            <div class="col-md-12 alert alert-danger">{{ $error }}</div>
                                        </div>
                                    @endforeach
                                @endif
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>رمز جدید <span class="text-danger">*</span></label>
                                        <input required type="text" name="new_password" class="form-control" placeholder="رمز جدید...">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button type="reset" class="btn btn-secondary">انصراف</button>
                                <button type="submit" class="btn btn-success">ذخیره</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@endsection
