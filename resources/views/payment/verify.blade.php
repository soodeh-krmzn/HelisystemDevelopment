@extends('parts.zero-master')
@section('title', 'میز کار')
@section('styles')
@endsection
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <b>شارژ اشتراک</b>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <div class="text-center">
                    {{ $payment->message($response, request()) }}
                    <a href="https://helionline.ir/logout" class="btn btn-block btn-danger">
                        <i class="fa fa-backward"></i> بازگشت به نرم افزار
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
