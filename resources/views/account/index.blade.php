@extends('parts.master')
@section('title', 'لیست مشترکین')
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
                                    <div class="col-md-3">
                                        <label class="form-label required">نام</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name') ?? $request->name }}" placeholder="نام...">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label required">نام خانوادگی</label>
                                        <input type="text" name="family" class="form-control"
                                            value="{{ old('family') ?? $request->family }}" placeholder="نام خانوادگی...">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label required">موبایل</label>
                                        <input type="text" name="mobile" class="form-control"
                                            value="{{ old('mobile') ?? $request->mobile }}" placeholder="موبایل...">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">نام مجموعه</label>
                                        <input type="text" name="center" class="form-control"
                                            value="{{ old('center') ?? $request->center }}" placeholder="نام مجموعه...">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">وضعیت</label>
                                        <select name="status[]" id="status-select" class="custom-select select2" multiple>
                                            <option value="">انتخاب کنید...</option>
                                            <option value="deactive" @selected($request->status ? in_array('deactive', request('status')) : false)>
                                                غیرفعال
                                            </option>
                                            <option value="active" @selected($request->status ? in_array('active', request('status')) : false)>
                                                فعال
                                            </option>
                                            <option value="suspend" @selected($request->status ? in_array('suspend', request('status')) : false)>
                                                تعلیق
                                            </option>
                                        </select>
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
                            <h3 class="card-title">لیست مشترکین ({{ price($accounts->total()) }})</h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <a href="{{ route('account.create') }}" class="btn btn-success btn-sm">
                                        ایجاد اشتراک
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive">

                            @if ($accounts->count() > 0)
                                <table class="table table-bordered table-hover m-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>نام</th>
                                            <th>تلفن</th>
                                            <th>شهر</th>
                                            <th>ثبت نام</th>
                                            <th>روزهای باقیمانده</th>
                                            <th> پیامک</th>
                                            <th>تاریخ شارژ</th>
                                            <th>وضعیت</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($accounts as $key => $account)
                                            <tr>
                                                <td>{{ per_number($accounts->firstItem() + $key) }}</td>
                                                <td>
                                                    #{{ $account->id }}
                                                    <br>
                                                    <strong>{{ $account->getFullName() }}</strong>
                                                    <br>
                                                    {{ $account->center }}
                                                </td>
                                                <td>
                                                    {{ $account->mobile }}
                                                    <br>
                                                    {{ $account->phone }}
                                                </td>
                                                <td>
                                                    {{ $account->city }} / {{ $account->town }}
                                                </td>
                                                <td>{{ per_number(Verta($account->created_at)->format('Y/m/d H:i:s')) }}
                                                </td>
                                                <td style="direction: ltr">
                                                    {{ per_number($account->daysLeft()) }}
                                                    /{{ per_number($account->days) }}
                                                    <br>({{ $account->package?->name??'نامشخص' }})
                                                </td>
                                                <td>
                                                    @if ($account->sms_charge)
                                                    {{ $account->sms_charge  }}<br>
                                                    ({{$account?->sms_package->name??"نامشخص"}})
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                <td>

                                                    {{ per_number($account->charge_date ? Verta($account->charge_date)->format('Y/m/d H:i:s') : '') }}
                                                </td>
                                                <td id="status-td" class="{{ $account->getStatus() }} text-center">
                                                    <select name="status" id="status" class="form-control"
                                                        data-account_id="{{ $account->id }}">
                                                        <option value="suspend" data-class="bg-warning"
                                                            {{ $account->status == 'suspend' ? 'selected' : '' }}>
                                                            درانظار تایید
                                                        </option>
                                                        <option value="active" data-class="bg-success"
                                                            {{ $account->status == 'active' ? 'selected' : '' }}>
                                                            فعال
                                                        </option>
                                                        <option value="deactive" data-class="bg-danger"
                                                            {{ $account->status == 'deactive' ? 'selected' : '' }}>
                                                            غیرفعال
                                                        </option>
                                                    </select>
                                                    @if ($account->status_detail)
                                                        <button class="btn btn-sm btn-info mt-2" data-toggle="tooltip"
                                                            data-placement="right" style="cursor: zoom-in"
                                                            title="{{ $account->status_detail }}"><i
                                                                class="fa fa-eye"></i></button>
                                                    @endif

                                                </td>
                                                <td>
                                                    <a href="{{ route('account.edit', $account) }}"
                                                        class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>
                                                    <a href="{{ $account->db_name ? '#' : route('account.showDatabase', $account) }}"
                                                        class="btn {{ $account->db_name ? 'btn-success' : 'btn-secondary' }} btn-sm"><i
                                                            class="fa fa-database"></i></a>
                                                    <a href="{{ route('user.index', ['account' => $account->id]) }}"
                                                        class="btn btn-info btn-sm"><i class="fa fa-users"></i></a>
                                                    <a href="https://helione.ir/login-as/Ux2kC5tptbhGO8KTGsc/{{ $account->id }}"
                                                        target="_blanck" class="btn btn-info btn-sm"><i
                                                            class="fa fa-send"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if ($accounts->hasPages())
                                    <div class="d-flex mt-3">
                                        <div class="mx-auto">
                                            {{ $accounts->withQueryString()->links() }}
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-danger m-2 text-center">
                                    موردی جهت نمایش موجود نیست.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- modal --}}
    <div class="modal fade" id="status_detail_modal" tabindex="-1" role="dialog" aria-labelledby="status_detailTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">علت</h5>
                    <button type="button" class="close float-left mx-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea id="status_detail" class="form-control" rows="5" placeholder="علت ورود به وضعیت..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit_status" class="btn btn-primary">ثبت</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document.body).on("change", "#status", function() {
                var status = $(this).find("option:selected").val();
                var classname = $(this).find("option:selected").data("class");
                var account = $(this).data("account_id");
                if (status != 'active') {
                    $('#status_detail_modal').modal();
                } else {
                    $.ajax({
                        type: "POST",
                        url: `/account-status/${account}`,
                        data: {
                            status: status,
                            status_detail: null,
                        },
                        success: function(data) {
                            location.reload(true);
                        },
                        error: function(data) {
                            alert("error");
                            console.log(data);
                        },
                    });
                }
                $(document.body).on("click", "#submit_status", function() {
                    $.ajax({
                        type: "POST",
                        url: `/account-status/${account}`,
                        data: {
                            status: status,
                            status_detail: $("#status_detail").val(),
                        },
                        success: function(data) {
                            location.reload(true);
                        },
                        error: function(data) {
                            alert("error");
                            console.log(data);
                        },
                    });
                });
            });
        });
    </script>
@endsection
