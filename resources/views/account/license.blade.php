@extends('parts.master')
@section('title', 'مجوز آفلاین')
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
                                لیست مجوزها {{ $account != '' ? 'اشتراک ' . $account->getFullName() : '' }}
                            </h3>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <div class="row">
                                <div class="col-md-12">
                                    @if ($users->count() > 0)
                                        <table class="table table-bordered table-hover m-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>مجوز</th>
                                                    <th>کد سیستم</th>
                                                    <th>وضعیت</th>
                                                    <th>وضعیت فعال</th>
                                                    <th>کاربر فعال</th>
                                                    {{-- <th>عملیات</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($licenses as $license)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td>{{ $license->license }}</td>
                                                        <td>{{ $license->systemCode }}</td>
                                                        <td>{{ $license->status == 0 ? 'غیر فعال' : 'فعال' }}</td>
                                                        <td>{{ $license->isActive == 0 ? 'خاموش' : 'در حال استفاده' }}</td>
                                                        <td id="status-td"
                                                            class="{{ $license->status == 0 ? 'bg-danger' : 'bg-success' }} text-center">
                                                            <select name="status" id="status" class="form-control"
                                                                data-account_id="{{ $license->account->id }}"
                                                                data-license_id="{{ $license->id }}">
                                                                <option value="1"
                                                                    {{ $license->status == 1 ? 'selected' : '' }}>فعال
                                                                </option>
                                                                <option value="0"
                                                                    {{ $license->status == 0 ? 'selected' : '' }}>غیرفعال
                                                                </option>
                                                            </select>
                                                        </td>
                                                        {{-- <td>
                                                            <a href="{{ route('user.changePassword', $user->id) }}"
                                                                class="btn btn-secondary btn-sm"><i
                                                                    class="fa fa-key"></i></a>
                                                            <a href="{{ route('user.edit', $user) }}"
                                                                class="btn btn-warning btn-sm"><i
                                                                    class="fa fa-pencil"></i></a>
                                                            <a href="{{ route('user.destroy', $user) }}"
                                                                class="btn btn-danger btn-sm" data-confirm-delete="true"><i
                                                                    class="fa fa-trash"></i></a>
                                                        </td> --}}
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="alert alert-danger text-center m-2">موردی جهت نمایش موجود نیست.</div>
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
                const status = $(this).val();
                const licenseId = $(this).data("license_id");
                const accountId = $(this).data("account_id");
                const rowElement = $(this).closest("td");

                $.ajax({
                    type: "POST",
                    url: `/account-license-status/${accountId}`,
                    data: {
                        license_id: licenseId,
                        status: status
                    },
                    success: function(response) {
                        rowElement
                            .removeClass("bg-danger bg-success")
                            .addClass(response.status == 1 ? "bg-success" : "bg-danger");
                        alert(response.message);
                    },
                    error: function(error) {
                        console.log(error);
                        alert("خطا در تغییر وضعیت لایسنس");
                    }
                });
            });
        });
    </script>
@endsection
