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
                            <h3 class="card-title">ایجاد کد تخفیف</h3>
                        </div>
                        <form action="{{ route('offer.update', $offer) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="row">
                                            <div class="col-md-12 alert alert-danger">{{ $error }}</div>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">نام کد <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old("name") ?? $offer->name }}" placeholder="نام کد...">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">نوع تخفیف <span class="text-danger">*</span></label>
                                        <select name="type" class="form-control">
                                            <option value="percent" {{ old("type") == "percent" ? "selected" : ($offer->type == "percent" ? "selected" : '') }}>درصد</option>
                                            <option value="price" {{ old("type") == "price" ? "selected" : ($offer->type == "price" ? "selected" : '') }}>مبلغ</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">مقدار تخفیف <span class="text-danger">*</span></label>
                                        <input type="text" name="per" class="form-control" placeholder="مقدار تخفیف..." value="{{ old("per") ?? $offer->per }}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">حداقل مبلغ <span class="text-danger">*</span></label>
                                        <input type="text" name="min_price" class="form-control" placeholder="حداقل مبلغ..." value="{{ old('min_price') ?? $offer->min_price }}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">نوع اعمال <span class="text-danger">*</span></label>
                                        <select name="package" class="form-control">
                                            <option {{ $offer->package == "all" ? "selected" : "" }} value="all">همه</option>
                                            <option {{ $offer->package == "account" ? "selected" : "" }} value="account">اشتراک</option>
                                            <option {{ $offer->package == "sms" ? "selected" : "" }} value="sms">پیامک</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">شروع <span class="text-danger">*</span></label>
                                        <input type="datetime-local" required name="start_at" class="form-control" placeholder="شروع..." value="{{ old("start_at") ?? $offer->start_at }}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">انقضا <span class="text-danger">*</span></label>
                                        <input type="datetime-local" required name="expire_at" class="form-control" placeholder="انقضا..." value="{{ old("expire_at") ?? $offer->expire_at }}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">توضیحات</label>
                                        <input type="text" name="details" class="form-control" placeholder="توضیحات..." value="{{ old("details") ?? $offer->details }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success me-sm-3 me-1">ثبت اطلاعات</button>
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
