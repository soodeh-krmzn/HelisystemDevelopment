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
                            <h3 class="card-title">ویرایش دسته پیام</h3>
                        </div>
                        <form action="{{ route('sms-pattern-category.update', $smsPatternCategory) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
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
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">عنوان <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old("name") ?? $smsPatternCategory->name }}" placeholder="عنوان...">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">ترتیب نمایش </label>
                                        <input type="text" name="display_order" class="form-control" value="{{ old('display_order') ?? $smsPatternCategory->display_order }}" placeholder="ترتیب نمایش...">
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
