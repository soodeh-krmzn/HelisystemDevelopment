@extends('parts.master')
@section('title', 'بسته های تعرفه')
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
                                ایجاد بسته تعرفه
                            </h3>
                        </div>
                        <form action="{{ route('package.store') }}" method="POST">
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
                                    <div class="col-md-4 form-group">
                                        <label>نام <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old("name") }}" placeholder="نام...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>نوع <span class="text-danger">*</span></label>
                                        <select name="type" class="form-control">
                                            <option value="account" {{ old("type") == "account" ? "selected" : "" }}>بسته اشتراکی</option>
                                            <option value="sms" {{ old("type") == "sms" ? "selected" : "" }}>بسته پیامک</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>توضیحات</label>
                                        <input type="text" name="details" class="form-control" value="{{ old("details") }}" placeholder="توضیحات...">
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                لیست بسته های تعرفه
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    @if ($packages->count() > 0)
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>نام</th>
                                                    <th>توضیحات</th>
                                                    <th>عملیات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($packages as $package)
                                                    <tr>
                                                        <td>{{ per_number($loop->index + 1) }}</td>
                                                        <td>{{ $package->name }}</td>
                                                        <td>{{ $package->details }}</td>
                                                        <td>
                                                            <a href="{{ route('package.edit', $package) }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>
                                                            <a href="{{route('package.destroy',$package)}}" data-confirm-delete="true" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                                            <a href="{{ route('package.menu', $package) }}" class="btn btn-info btn-sm">تخصیص منو</a>
                                                            <a href="{{ route('package-price.index', ['package' => $package->id]) }}" class="btn btn-success btn-sm">تعرفه</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="alert alert-danger m-2 text-center">موردی جهت نمایش موجود نیست.</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@endsection
