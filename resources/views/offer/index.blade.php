@extends('parts.master')
@section('title', 'کد تخفیف')
@section('styles')
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ایجاد کد تخفیف</h3>
                        </div>
                        <form action="{{ route('offer.store') }}" method="post">
                            @csrf
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
                                        <label class="form-label required">نام کد <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                               placeholder="نام کد...">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">نوع تخفیف <span class="text-danger">*</span></label>
                                        <select name="type" class="form-control">
                                            <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>
                                                درصد
                                            </option>
                                            <option value="price" {{ old('type') == 'price' ? 'selected' : '' }}>مبلغ
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">مقدار تخفیف <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="per" class="form-control" placeholder="مقدار تخفیف..."
                                               value="{{ old('per') }}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">حداقل مبلغ <span class="text-danger">*</span></label>
                                        <input type="text" name="min_price" class="form-control"
                                               placeholder="حداقل مبلغ..." value="{{ old('min_price') }}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">نوع اعمال <span class="text-danger">*</span></label>
                                        <select name="package" class="form-control">
                                            <option {{ old('package') == "all" ? "selected" : "" }} value="all">همه</option>
                                            <option {{ old('package') == "account" ? "selected" : "" }} value="account">اشتراک</option>
                                            <option {{ old('package') == "sms" ? "selected" : "" }} value="sms">پیامک</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">شروع <span class="text-danger">*</span></label>
                                        <input type="datetime-local" required name="start_at" class="form-control" placeholder="شروع..." value="{{ old('start_at') }}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">انقضا <span class="text-danger">*</span></label>
                                        <input type="datetime-local" required name="expire_at" class="form-control" placeholder="انقضا..."
                                               value="{{ old('expire_at') }}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">توضیحات</label>
                                        <input type="text" name="details" class="form-control" placeholder="توضیحات..."
                                               value="{{ old('details') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success me-sm-3 me-1">ثبت اطلاعات</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-body p-0 table-responsive">
                            <?php
                            if ($offers->count() > 0) {
                                ?>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ردیف</th>
                                            <th>نام کد</th>
                                            <th>نوع تخفیف</th>
                                            <th>مقدار تخفیف</th>
                                            <th>حداقل مبلغ</th>
                                            <th>توضیحات</th>
                                            <th>شروع</th>
                                            <th>انقضا</th>
                                            <th>نوع اعمال</th>
                                            <th>مدیریت</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($offers as $offer) { ?>
                                            <tr>
                                                <td><?php echo per_number($i); ?></td>
                                                <td><?php echo $offer->name; ?></td>
                                                <td><?php echo $offer->type; ?></td>
                                                <td><?php echo per_number(number_format($offer->per)); ?></td>
                                                <td><?php echo per_number(number_format($offer->min_price)); ?></td>
                                                <td><?php echo $offer->details; ?></td>
                                                <td><?php echo per_number($offer->start_at ? Verta($offer->start_at)->format("Y/m/d H:i:s") : '') ?></td>
                                                <td><?php echo per_number($offer->expire_at ? Verta($offer->expire_at)->format("Y/m/d H:i:s") : '') ?></td>
                                                <td>
                                                    <?php
                                                    if ($offer->package == "account") {
                                                        echo "اشتراک";
                                                    } else if ($offer->package == "sms") {
                                                        echo "پیامک";
                                                    } else if ($offer->package == "all") {
                                                        echo "همه";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="{{ route('offer.edit', $offer) }}" class="btn btn-warning btn-sm"><i
                                                            class="fa fa-edit"></i></a>
                                                    <button type="button" class="btn btn-danger btn-sm delete-offer"
                                                            data-id="<?php echo $offer->id; ?>"><i class="fa fa-remove"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php
                                        $i++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                            } else { ?>
                                <div class="alert alert-danger text-center">موردی جهت نمایش موجود نیست.</div>
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
