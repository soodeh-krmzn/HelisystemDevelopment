@extends('parts.master')
@section('title', 'میز کار')
@section('styles')
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ایجاد اشتراک جدید</h3>
                        </div>
                        <form action="{{ route('account.store') }}" method="POST">
                            <div class="card-body">
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
                                    <div class="col-md-3 form-group">
                                        <label class="form-label">نام <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old("name") }}" placeholder="نام...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>نام خانوادگی <span class="text-danger">*</span></label>
                                        <input type="text" name="family" class="form-control" value="{{ old("family") }}" placeholder="نام خانوادگی...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>نام مرکز <span class="text-danger">*</span></label>
                                        <input type="text" name="center" class="form-control" value="{{ old("center") }}" placeholder="نام مرکز...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>تلفن ثابت <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" value="{{ old("phone") }}" placeholder="تلفن ثابت..." maxlength="11">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label>موبایل <span class="text-danger">*</span></label>
                                        <input type="text" name="mobile" class="form-control" value="{{ old("mobile") }}" placeholder="موبایل..." maxlength="11">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>تعداد روز <span class="text-danger">*</span></label>
                                        <input type="text" name="days" class="form-control" value="{{ old("days") }}" placeholder="تعداد روز...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>استان <span class="text-danger">*</span></label>
                                        <input type="text" name="city" class="form-control" value="{{ old("city") }}" placeholder="استان...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>شهر <span class="text-danger">*</span></label>
                                        <input type="text" name="town" class="form-control" value="{{ old("town") }}" placeholder="شهر...">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>آدرس</label>
                                        <textarea name="address" class="form-control" rows="1" placeholder="آدرس...">{{ old("address") }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label>شارژ پیامک</label>
                                        <input type="text" name="sms_charge" class="form-control" value="{{ old("sms_charge") }}" placeholder="شارژ پیامک...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>تاریخ شارژ</label>
                                        <input type="text" name="charge_date" class="form-control" value="{{ old("charge_date") }}" placeholder="تاریخ شارژ...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>نام گروه</label>
                                        <input type="text" name="group_id" class="form-control" value="{{ old("group_id") }}" placeholder="نام گروه...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>نامک</label>
                                        <input type="text" name="slug" class="form-control" value="{{ old("slug") }}" placeholder="نامک...">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>نام پایگاه داده</label>
                                        <input type="text" name="db_name" class="form-control" value="{{ old("db_name") }}" placeholder="نام پایگاه داده...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>نام کاربری پایگاه داده</label>
                                        <input type="text" name="db_user" class="form-control" value="{{ old("db_user") }}" placeholder="نام کاربری پایگاه داده...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>رمز پایگاه داده</label>
                                        <input type="text" name="db_pass" class="form-control" value="{{ old("db_pass") }}" placeholder="رمز پایگاه داده...">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>مرچنت کد زرین پال</label>
                                        <input type="text" name="zarinpal" class="form-control" value="{{ old("zarinpal") }}" placeholder="مرچنت کد زرین پال...">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="reset" class="btn btn-secondary">انصراف</button>
                                        <button type="submit" class="btn btn-success">ذخیره</button>
                                    </div>
                                </div>
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
