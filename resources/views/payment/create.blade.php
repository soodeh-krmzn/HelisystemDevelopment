@extends('parts.zero-master')
@section('title', 'میز کار')
@section('styles')
@endsection
@section('content')
    <div class="login-box">
        <div class="login-logo">
            @if ($type == "account")
                <b>شارژ اشتراک</b>
            @elseif ($type == "sms")
                <b>شارژ پیامک</b>
            @endif
        </div>
        <div class="row mb-3">
            <div class="col-12 text-center">
                <p class="login-box-msg">بسته مورد نیاز خود را انتخاب و بر روی کلید خرید کلیک نمایید.</p>
                @if ($packages->count() > 0)
                    <select name="package_id" id="change-package" class="form-control">
                        @foreach ($packages as $package)
                            <option @selected(get_option('default_package')==$package->id ) value="{{ $package->id }}">{{ $package->name }}</option>
                        @endforeach
                    </select>
                @else
                    <div class="alert alert-danger">هیچ نوع اشتراکی تعریف نشده است.</div>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <div id="package-result">{{ $packagePrice->form($list, $user->id) }}</div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let loading = `
            <div class="col text-center">
                <i class='fa fa-refresh fa-spin fa-2x'></i>
            </div>`;

            $(document.body).on('change', '#change-package', function() {
                $("#package-result").html(loading);
                var package_id = $(this).val();
                console.log("{{ $user->id }}");
                $.ajax({
                    type: "POST",
                    url: "{{ route('package.change') }}",
                    data: {
                        package_id: package_id,
                        user_id: "{{ $user->id }}",
                        type:"{{request('type')}}"
                    },
                    success: function(data) {
                        $("#package-result").html(data);
                    }
                });
            });

            $(document.body).on('click', '#check-offer', function() {
                var offer_code = $('#offer-code').val();
                var type = $('#type').val();
                console.log(type);
                var package_price_id = $('#package-price-id').val();
                if (offer_code) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('offer.check') }}",
                        data: {
                            type: type,
                            offer_code: offer_code,
                            package_price_id: package_price_id
                        },
                        success: function(data) {
                            $('#price').val(data.final_price);
                            $('#offer-price').html('<b>مبلغ تخفیف: </b>' + data.offer_price);
                            alert('کد تخفیف اعمال شد.');
                        },
                        error: function(data) {
                            $("#price").val(data.responseJSON.price);
                            $("#offer-price").html('');
                            alert(data.responseJSON.message);
                        }
                    });
                } else {
                    alert('please insert offer code');
                }
            });

            $(document.body).on('click', '.select-package', function() {
                var price = $(this).data('price');
                var off_price = $(this).data('off_price');
                var package_price_id = $(this).data('id');
                $('#package-price-id').val(package_price_id);
                $('#type').val("{{ $type }}");
                if (price >= off_price) {
                    if (off_price <= 0) {
                        $('#price').val(price);
                    } else {
                        $('#price').val(off_price);
                    }
                } else if (price < off_price) {
                    if (price <= 0) {
                        $('#price').val(off_price);
                    } else {
                        $('#price').val(price);
                    }
                }
            });

        });
    </script>
@endsection
