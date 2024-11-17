@extends('parts.master')
@section('title', 'سوابق بازدید')
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
                                    <div class="col-md-4 form-group">
                                        <label class="form-label">حساب ها</label>
                                        <select name="account" class="custom-select form-control select2">
                                            <option value="">انتخاب کنید</option>
                                            @foreach ($accounts as $account)
                                                <option
                                                    @selected(request('account') == $account->id) value="{{ $account->id }}">
                                                    {{ $account->center }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">IP</label>
                                        <input type="text" name="ip" class="form-control"
                                               value="{{ old('ip') ?? $request->ip }}" placeholder="ip...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">سیستم عامل</label>
                                        <input type="text" name="os" class="form-control"
                                               value="{{ old('os') ?? $request->os }}" placeholder="دستگاه...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">مرورگر</label>
                                        <input type="text" name="browser" class="form-control"
                                               value="{{ old('browser') ?? $request->browser }}"
                                               placeholder="مرورگر...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">از تاریخ</label>
                                        <input id="from" type="text" name="from" class="form-control"
                                               value="{{ old('from') ?? $request->from }}" placeholder="از...">
                                    </div>
                                    <div class="col-md-4 form-group">
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
                                        <a href="{{route('user.visitRecord',$query)}}"
                                           class="btn btn-warning me-sm-3 me-1"> واحدسازی</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">سوابق بازدید ({{price( $records->total()) }})</h3>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            @if ($records->count() > 0)
                                @if ($request->unique==true)
                                    <table class="table table-bordered table-hover m-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>مشترک</th>
                                            <th>تعداد بازدید</th>
                                            <th>تاریخ آخرین بازدید</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($records as $key=> $record)
                                            <tr>
                                                <td>{{ per_number($records->firstItem() +$key ) }}</td>
                                                <td>{{ $record->account?->center }}</td>
                                                <td>{{ per_number($record->count) }}</td>
                                                <td>{{ persianTime($record->max_created_at) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <table class="table table-bordered table-hover m-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>مشترک</th>
                                            <th>کاربر</th>
                                            <th>مسیر</th>
                                            <th>آدرس</th>
                                            <th>IP</th>
                                            <th>مرورگر</th>
                                            <th>دستگاه</th>
                                            <th>تاریخ و ساعت</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($records as $key=> $record)
                                            <tr>
                                                <td>{{ per_number($records->firstItem() +$key ) }}</td>
                                                <td>{{ $record->account?->center }}</td>
                                                <td>{{ $record->user?->getFullName() }}</td>
                                                <td>{{ $record->route }}</td>
                                                <td>{{ $record->url }}</td>
                                                <td>{{ $record->ip }}</td>
                                                <td>{{ $record->browser }}</td>
                                                <td>{{ $record->device }}</td>
                                                <td>{{ per_number($record->created_at ? Verta($record->created_at)->format('Y/m/d H:i:s') : '') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                @if ($records->hasPages())
                                    <div class="d-flex mt-3">
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
    </section>
@endsection
@section('scripts')
@endsection
