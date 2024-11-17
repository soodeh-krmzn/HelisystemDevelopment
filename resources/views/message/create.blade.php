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
                                ارسال پیامک
                            </h3>
                        </div>
                        <form action="{{ route('message.store') }}" method="POST">
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
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">انتخاب اشتراک</label>
                                        <select name="account_id" id="account-id" class="form-control">
                                            <option value="0">هیچکدام</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}" {{ ($account->id == old("account_id")) ? 'selected' : '' }} data-mobile="{{ $account->mobile }}" data-name="{{ $account->name }}" data-family="{{ $account->family }}">{{ $account->getFullName() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">موبایل</label>
                                        <input type="text" name="mobile" id="mobile" class="form-control" placeholder="موبایل..." value="{{ old("mobile") }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">نام</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="نام..." value="{{ old("name") }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">نام خانوادگی</label>
                                        <input type="text" name="family" id="family" class="form-control" placeholder="نام خانوادگی..." value="{{ old("family") }}">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">انتخاب متن آماده</label>
                                        <select name="select_text" id="select-text" class="form-control">
                                            <option value="" data-text="">هیچکدام</option>
                                            @foreach ($texts as $text)
                                                <option data-text="{{ $text->text }}">{{ $text->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">متن</label>
                                        <input type="text" name="text" id="text" class="form-control" placeholder="متن..." value="{{ old("text") }}">
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
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $("#account-id").change(function() {
                var name = $(this).find("option:selected").data("name");
                var family = $(this).find("option:selected").data("family");
                var mobile = $(this).find("option:selected").data("mobile");

                $("#name").val(name);
                $("#family").val(family);
                $("#mobile").val(mobile);
            });

            $("#select-text").change(function() {
                var text = $(this).find("option:selected").data("text");

                $("#text").val(text);
            });
        });
    </script>
@endsection
