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
                            <h3 class="card-title">ویرایش اشتراک {{ $account->getFullName() }}</h3>
                        </div>
                        <form action="{{ route('account.update', $account) }}" method="POST">
                            <div class="card-body">
                                @csrf
                                @method('PUT')
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
                                        <input type="text" name="name" class="form-control" value="{{ old("name") ?? $account->name }}" placeholder="نام...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>نام خانوادگی <span class="text-danger">*</span></label>
                                        <input type="text" name="family" class="form-control" value="{{ old("family") ?? $account->family }}" placeholder="نام خانوادگی...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>نام مرکز <span class="text-danger">*</span></label>
                                        <input type="text" name="center" class="form-control" value="{{ old("center") ?? $account->center }}" placeholder="نام مرکز...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>تلفن ثابت <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" value="{{ old("phone") ?? $account->phone }}" placeholder="تلفن ثابت..." maxlength="11">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label>موبایل <span class="text-danger">*</span></label>
                                        <input type="text" name="mobile" class="form-control" value="{{ old("mobile") ?? $account->mobile }}" placeholder="موبایل..." maxlength="11">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>تعداد روز <span class="text-danger">*</span></label>
                                        <input type="text" name="days" class="form-control" value="{{ old("days") ?? $account->days }}" placeholder="تعداد روز...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>استان <span class="text-danger">*</span></label>
                                        <input type="text" name="city" class="form-control" value="{{ old("city") ?? $account->city }}" placeholder="استان...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>شهر <span class="text-danger">*</span></label>
                                        <input type="text" name="town" class="form-control" value="{{ old("town") ?? $account->town }}" placeholder="شهر...">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>آدرس</label>
                                        <textarea name="address" class="form-control" rows="1" placeholder="آدرس...">{{ old("address") ?? $account->address }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    {{-- <div class="col-md-3 form-group">
                                        <label>بسته پیامک</label>
                                        <select name="sms_package" class="custom-select">
                                            <option value="">نامشخص</option>
                                            @foreach($sms_packages as $sms_package)
                                            <option @selected($account->sms_package_id==$sms_package->id) value="{{$sms_package->id}}">{{$sms_package->name}}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="col-md-3 form-group">
                                        <label>شارژ پیامک</label>
                                        <input type="text" name="sms_charge" class="form-control" value="{{ old("sms_charge") ?? $account->sms_charge }}" placeholder="شارژ پیامک...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>نوع حساب</label>
                                        <select name="account_package" class="custom-select">
                                            <option value="">نامشخص</option>
                                            @foreach($account_packages as $account_package)
                                            <option @selected($account->package_id==$account_package->id) value="{{$account_package->id}}">{{$account_package->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>تاریخ شارژ</label>
                                        <input type="text" name="charge_date" class="form-control" value="{{ old("charge_date") ?? $account->charge_date }}" placeholder="تاریخ شارژ...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>نام گروه</label>
                                        <input type="text" name="group_id" class="form-control" value="{{ old("group_id") ?? $account->group_id }}" placeholder="نام گروه...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>نامک</label>
                                        <input type="text" name="slug" class="form-control" value="{{ old("slug") ?? $account->slug }}" placeholder="نامک...">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>مرچنت کد زرین پال</label>
                                        <input type="text" name="zarinpal" class="form-control" value="{{ old("zarinpal") ?? $account->zarinpal }}" placeholder="مرچنت کد زرین پال...">
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
