@extends('parts.master')
@section('title', 'مجوز آفلاین')
@section('styles')
    <style>
        .scrollable {
            max-width: 200px;
            max-height: 50px;
            overflow: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
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
                                    @if ($licenses->count() > 0)
                                        <table class="table table-bordered table-hover m-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>مجوز</th>
                                                    <th>کد سیستم</th>
                                                    <th>وضعیت</th>
                                                    <th>وضعیت فعال</th>
                                                    <th>کاربر فعال</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($licenses as $license)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td class="scrollable">{{ $license->license }}</td>
                                                        <td>{{ $license->systemCode }}</td>
                                                        <td>
                                                            <button
                                                                class="btn btn-sm license-status-btn {{ $license->status ? 'btn-success' : 'btn-danger' }}"
                                                                data-license-id="{{ $license->id }}"
                                                                data-license-status="{{ $license->status }}">
                                                                {{ $license->status ? 'فعال' : 'غیرفعال' }}
                                                            </button>
                                                        </td>
                                                        <td class="badge bg-{{ $license->isActive == 0 ? 'secondary' : 'success'}}">{{ $license->isActive == 0 ? 'خاموش' : 'در حال استفاده' }}</td>
                                                        <td>{{ optional(findUser($license->userActive))->getFullName() }}
                                                        </td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $(document.body).on("click", ".license-status-btn", function() {
                const button = $(this);
                const licenseId = button.data("license-id");
                const currentStatus = button.data("license-status");
                const newStatus = currentStatus === 1 ? 0 : 1;

                Swal.fire({
                    title: 'آیا مطمئن هستید؟',
                    text: `وضعیت لایسنس به ${newStatus ? 'فعال' : 'غیرفعال'} تغییر خواهد کرد.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'بله تغییر بده',
                    cancelButtonText: 'لغو'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: `/account-license-status/${licenseId}`,
                            data: {
                                status: newStatus,
                                _token: $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function(response) {
                                button.data("license-status", newStatus);
                                button.text(newStatus ? 'فعال' : 'غیرفعال');
                                button
                                    .toggleClass("btn-success", newStatus === 1)
                                    .toggleClass("btn-danger", newStatus === 0);

                                Swal.fire(
                                    'موفق',
                                    'وضعیت لایسنس با موفقیت تغییر کرد.',
                                    'success'
                                );
                            },
                            error: function(jqXHR) {
                                let errorMessage = jqXHR.responseJSON && jqXHR
                                    .responseJSON.error ?
                                    jqXHR.responseJSON.error :
                                    "{{ __('خطا در ارتباط با سرور.') }}";
                                Swal.fire({
                                    title: "{{ __('اخطار') }}",
                                    text: errorMessage,
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
