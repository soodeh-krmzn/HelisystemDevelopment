@extends('parts.master')
@section('title', 'ایجاد متن آماده')
@section('styles')
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ایجاد متن آماده</h3>
                        </div>
                        <form action="{{ route('message-text.store') }}" method="post">
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
                                        <label class="form-label required">عنوان <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="عنوان...">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">متن <span class="text-danger">*</span></label>
                                        <textarea name="text" id="text" rows="1" class="form-control" placeholder="متن...">{{ old('text') }}</textarea>
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
                            if ($messageTexts->count() > 0) { ?>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ردیف</th>
                                            <th>عنوان</th>
                                            <th>متن</th>
                                            <th>مدیریت</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($messageTexts as $messageText) { ?>
                                            <tr>
                                                <td><?php echo per_number($i); ?></td>
                                                <td><?php echo $messageText->name; ?></td>
                                                <td><?php echo $messageText->text; ?></td>
                                                <td>
                                                    <a href="{{ route('message-text.edit', $messageText) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                                    <a href="{{ route('message-text.destroy', $messageText) }}" class="btn btn-danger btn-sm" data-confirm-delete="true"><i class="fa fa-remove"></i></a>
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
