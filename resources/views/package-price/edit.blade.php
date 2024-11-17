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
                            <h3 class="card-title">
                                ویرایش تعرفه
                            </h3>
                        </div>
                        <form action="{{ route('package-price.update', $packagePrice) }}" method="POST">
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
                                    <div class="col-md-4 form-group">
                                        <label>تعداد روز <span class="text-danger">*</span></label>
                                        <input type="text" name="days" class="form-control" value="{{ old("days") ?? $packagePrice->days }}" placeholder="تعداد روز...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>قیمت <span class="text-danger">*</span></label>
                                        <input type="text" name="price" class="form-control" value="{{ old("price") ?? $packagePrice->price }}" placeholder="قیمت...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>قیمت با تخفیف</label>
                                        <input type="text" name="off_price" class="form-control" value="{{ old("off_price") ?? $packagePrice->off_price }}" placeholder="قیمت با تخفیف...">
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
