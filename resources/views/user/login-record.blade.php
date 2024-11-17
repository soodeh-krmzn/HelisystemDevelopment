@extends('parts.master')
@section('title', 'سوابق ورود')
@section('styles')
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">فیلتر </h3>
                        </div>
                        <form>
                            <div class="card-body">
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="row">
                                            <div class="col-md-12 alert alert-danger">
                                                {{ $error }}
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label required">نام کاربری</label>
                                        <input type="text" name="username" class="form-control"
                                            value="{{ old('username') ?? $request->username }}" placeholder="نام کاربری...">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">IP</label>
                                        <input type="text" name="ip" class="form-control"
                                            value="{{ old('ip') ?? $request->ip }}" placeholder="ip...">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">سیستم عامل</label>
                                        <input type="text" name="os" class="form-control"
                                            value="{{ old('os') ?? $request->os }}" placeholder="دستگاه...">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">مرورگر</label>
                                        <input type="text" name="browser" class="form-control"
                                            value="{{ old('browser') ?? $request->browser }}" placeholder="مرورگر...">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">از تاریخ</label>
                                        <input id="from" type="text" name="from" class="form-control"
                                            value="{{ old('from') ?? $request->from }}" placeholder="از...">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">تا تاریخ</label>
                                        <input id="to" type="text" name="to" class="form-control"
                                            value="{{ old('to') ?? $request->to }}" placeholder="تا...">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success me-sm-3 me-1">فیلتر</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">سوابق ورود ({{ price($records->total()) }})</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    @if ($records->count() > 0)
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>وضعیت</th>
                                                    <th>نام کاربری</th>
                                                    <th>رمز</th>
                                                    <th>IP</th>
                                                    <th>مرورگر</th>
                                                    <th>سیستم عامل</th>
                                                    <th>تاریخ و ساعت</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($records as $key => $record)
                                                    <tr>
                                                        <td>{{ per_number($records->firstItem() + $key) }}</td>
                                                        <td>{!! $record->status == 'success'
                                                            ? "<span class='badge bg-success'>موفق</span>"
                                                            : "<span class='badge bg-danger'>ناموفق</span>" !!}</td>
                                                        <td>{{ $record->username }}</td>
                                                        <td>{{ $record->password }}</td>
                                                        <td>{{ $record->ip }}</td>
                                                        <td>{{ $record->browser }}</td>
                                                        <td>{{ $record->device }}</td>
                                                        <td>{{ per_number($record->created_at ? Verta($record->created_at)->format('Y/m/d H:i:s') : '') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if ($records->hasPages())
                                        <div class="d-flex">
                                            <div class="mx-auto">
                                                {{ $records->withQueryString()->links() }}
                                            </div>
                                        </div>
                                    @endif
                                    @else
                                        <div class="alert alert-danger m-0 text-center">موردی جهت نمایش موجود نیست.</div>
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
