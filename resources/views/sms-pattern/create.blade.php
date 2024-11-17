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
                                ایجاد الگوی پیام جدید
                            </h3>
                        </div>
                        <form action="{{ route('sms-pattern.store') }}" method="POST">
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
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">دسته <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-control">
                                            @foreach ($smsPatternCategories as $category)
                                                <option {{ ($category->id == old("category_id")) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">نام <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="نام..." value="{{ old("name")}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">صفحه <span class="text-danger">*</span></label>
                                        <input type="number" name="page" class="form-control" placeholder="صفحه..." value="{{ old("page") }}" min="1">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">هزینه <span class="text-danger">*</span></label>
                                        <input type="text" name="cost" class="form-control" placeholder="هزینه..." value="{{ old("cost") }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label class="form-label required">متن پیام <span class="text-danger">*</span></label>
                                        <textarea name="text" class="form-control" placeholder="متن پیام...">{{ old("text") }}</textarea>
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
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@endsection
