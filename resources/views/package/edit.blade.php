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
                                ویرایش بسته {{ $package->name }}
                            </h3>
                        </div>
                        <form action="{{ route('package.update', $package) }}" method="POST">
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
                                        <label>نام <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old("name") ?? $package->name }}" placeholder="نام...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>نوع <span class="text-danger">*</span></label>
                                        <select name="type" class="form-control">
                                            <option value="account" {{ old("type") == "account" ? "selected" : ($package->type == "account" ? "selected" : "") }}>بسته اشتراکی</option>
                                            <option value="sms" {{ old("type") == "sms" ? "selected" : ($package->type == "sms" ? "selected" : "") }}>بسته پیامک</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>توضیحات</label>
                                        <input type="text" name="details" class="form-control" value="{{ old("details") ?? $package->details }}" placeholder="توضیحات...">
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
    </div>
</section>
@endsection
@section('scripts')
@endsection
