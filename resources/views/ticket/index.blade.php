@extends('parts.master')
@section('title', 'تیکت ها')
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
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">موضوع</label>
                                        <input type="text" name="subject" class="form-control"
                                               value="{{ old('subject') ?? $request->subject }}" placeholder="موضوع...">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">وضعیت</label>
                                        <select name="status[]" id="status-select" class="custom-select select2"
                                                multiple>
                                            <option value="">انتخاب کنید...</option>
                                            <option
                                                value="in-progress" @selected($request->status ? in_array('in-progress', request('status')) : false)>
                                                درحال بررسی
                                            </option>
                                            <option
                                                value="closed" @selected($request->status ? in_array('closed', request('status')) : false)>
                                                بستن
                                            </option>
                                            <option
                                                value="waiting-for-expert" @selected($request->status ? in_array('waiting-for-expert', request('status')) : false)>
                                                درانتظار کارشناس
                                            </option>
                                            <option
                                                value="waiting-for-customer" @selected($request->status ? in_array('waiting-for-customer', request('status')) : false)>
                                                درانتظار مشتری
                                            </option>
                                            <option
                                                value="waiting-for-reply"@selected($request->status ? in_array('waiting-for-reply', request('status')) : false)>
                                                درانتظار پاسخ
                                            </option>
                                        </select>
                                    </div>
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
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">تیکت ها ({{ price($tickets->total()) }})</h3>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <?php
                            if ($tickets->count() > 0) { ?>
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>شماره</th>
                                    <th>موضوع</th>
                                    <th>وضعیت</th>
                                    <th>حساب</th>
                                    <th>تاریخ ثبت</th>
                                    <th>تاریخ آخرین پیام</th>
                                    <th>مدیریت</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($tickets as $key => $ticket)
                                    <tr>
                                        <td>{{ per_number($tickets->firstItem() + $key) }}</td>
                                        <td>{{ $ticket->id }}</td>
                                        <td>{{ $ticket->subject }}</td>
                                        <td>
                                            @if ($ticket->status == "closed")
                                                <span class="badge bg-dark">@lang($ticket->status)</span>
                                            @elseif ($ticket->status == "waiting-for-expert")
                                                <span class="badge bg-warning">@lang($ticket->status)</span>
                                            @elseif ($ticket->status == "waiting-for-customer")
                                                <span class="badge bg-primary">@lang($ticket->status)</span>
                                            @elseif ($ticket->status == "waiting-for-reply")
                                                <span class="badge bg-danger">@lang($ticket->status)</span>
                                            @elseif ($ticket->status == "in-progress")
                                                <span class="badge bg-info">@lang($ticket->status)</span>
                                            @endif
                                        </td>
                                        <td>{{ $ticket->account?->center }}</td>
                                        <td>{{ persianTime($ticket->created_at) }}</td>
                                        <td>{{ per_number($ticket->lastMsgTime()) }}</td>
                                        <td>
                                            <a href="{{ route('openChat', $ticket->id) }}"
                                               class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @if ($tickets->hasPages())
                                <div class="d-flex mt-3">
                                    <div class="mx-auto">
                                        {{ $tickets->withQueryString()->links() }}
                                    </div>
                                </div>
                            @endif
                                <?php
                            } else { ?>
                            <div class="alert alert-danger text-center m-2">موردی جهت نمایش موجود نیست.</div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@endsection
