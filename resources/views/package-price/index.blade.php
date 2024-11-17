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
                                ایجاد تعرفه {{ $package != '' ? "بسته " . $package->name : '' }}
                            </h3>
                        </div>
                        <form action="{{ route('package-price.store') }}" method="POST">
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
                                        <label>تعداد روز <span class="text-danger">*</span></label>
                                        <input type="text" name="days" class="form-control" value="{{ old("days") }}" placeholder="تعداد روز...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>قیمت <span class="text-danger">*</span></label>
                                        <input type="text" name="price" class="form-control" value="{{ old("price") }}" placeholder="قیمت...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>قیمت با تخفیف</label>
                                        <input type="text" name="off_price" class="form-control" value="{{ old("off_price") }}" placeholder="قیمت با تخفیف...">
                                    </div>
                                </div>
                                @if ($package == '')
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label>بسته</label>
                                            <select name="package_id" class="form-control">
                                                @foreach (\App\Models\Package::all() as $item)
                                                    <option value="{{ $item->id }}" {{ old("package_id") == $item->id ? "selected" : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                                @endif
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
                                لیست تعرفه {{ $package != '' ? "بسته " . $package->name : '' }}
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    @if ($prices->count() > 0)
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>تعداد روز</th>
                                                    <th>قیمت</th>
                                                    <th>قیمت با تخفیف</th>
                                                    <th>عملیات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($prices as $price)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td>{{ number_format($price->days) }}</td>
                                                        <td>{{ number_format($price->price) }}</td>
                                                        <td>{{ number_format($price->off_price) }}</td>
                                                        <td>
                                                            <a href="{{ route('package-price.edit', $price) }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>
                                                            <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="alert alert-danger m-2p text-center">
                                            موردی جهت نمایش موجود نیست.
                                        </div>
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
