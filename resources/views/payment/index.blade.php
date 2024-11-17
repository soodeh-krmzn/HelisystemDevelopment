@extends('parts.master')
@section('title', 'گزارش پرداخت ها')
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
                                        <label class="form-label">وضعیت</label>
                                        <select name="status" class="custom-select">
                                            <option value="">انتخاب کنید...</option>
                                            <option @selected($request->status=='ok') value="ok">موفق</option>
                                            <option
                                                @selected($request->filled('status') and $request->status!='ok') value="faild">
                                                ناموفق
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">حساب ها</label>
                                        <select name="account" class="custom-select form-control select2">
                                            <option value="">انتخاب کنید</option>
                                            @foreach ($accounts as $account)
                                                <option
                                                    @selected($request->account == $account->id) value="{{ $account->id }}">
                                                    {{ $account->center }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">نوع</label>
                                        <select name="type" class="custom-select form-control">
                                            <option value="">همه</option>
                                            <option @selected($request->type == "account") value="account">اشتراک</option>
                                            <option @selected($request->type == "sms") value="sms">پیامک</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label required">از تاریخ</label>
                                        <input id="from" type="text" name="from" class="form-control"
                                               value="{{ old('from') ?? $request->from }}" placeholder="از...">
                                    </div>
                                    <div class="col-md-6">
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
                            <h3 class="card-title">گزارش پرداخت ها ({{price($payments->total())}})</h3>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            @if ($payments->count() > 0)
                                <table class="table table-bordered table-hover m-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>شناسه پرداخت</th>
                                        <th>وضعیت</th>
                                        <th>رسید دیجیتال</th>
                                        <th>مبلغ</th>
                                        <th>نوع</th>
                                        <th>درگاه</th>
                                        <th>کاربر</th>
                                        <th>نام کاربری</th>
                                        <th>مشترک</th>
                                        <th>تاریخ و ساعت</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($payments as $key=> $payment)
                                        <tr>
                                            <td>{{ per_number($payments->firstItem() + $key) }}</td>
                                            <td>{{ ltrim(str_replace('A', '', $payment->authority), '0') }}</td>
                                            <td>{!! $payment->status == 'OK'
                                                            ? "<span class='badge bg-success'>موفق</span>"
                                                            : "<span class='badge bg-danger'>ناموفق</span>" !!}</td>
                                            <td>{{ $payment->ref_id }}</td>
                                            <td>{{ per_number(number_format($payment->price)) }}</td>
                                            <td>{{ $payment->type }}</td>
                                            <td>{{ $payment->driver }}</td>
                                            <td>{{ $payment->user?->getFullName() }}</td>
                                            <td>{{ $payment->username }}</td>
                                            <td>{{ $payment->account?->center }}</td>
                                            <td>{{ per_number($payment->created_at ? Verta($payment->created_at)->format('Y/m/d H:i:s') : '') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="100%"><h3 class="text-center m-0">جمع
                                                کل: {{ price($sumPrice) }}</h3></td>
                                    </tr>
                                    </tfoot>
                                </table>
                                @if ($payments->hasPages())
                                    <div class="d-flex mt-3">
                                        <div class="mx-auto">
                                            {{ $payments->withQueryString()->links() }}
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-danger m-2 text-center">موردی جهت نمایش موجود نیست.</div>
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
