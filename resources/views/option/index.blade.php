@extends('parts.master')
@section('title', 'تنظیمات')
@section('styles')
    <style>
        .ck-editor__editable {
            height: 280px;
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
                            <h3 class="card-title">تنظیمات</h3>
                        </div>
                        <form action="{{ route('option.store') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label>حالت بروزرسانی</label>
                                        <select name="update_mode" class="form-control">
                                            <option
                                                {{ $option->get_option('update_mode') == 0 ? 'selected' : '' }} value="0">
                                                غیرفعال
                                            </option>
                                            <option
                                                {{ $option->get_option('update_mode') == 1 ? 'selected' : '' }} value="1">
                                                فعال
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>عنوان سایت</label>
                                        <input type="text" name="title" class="form-control" placeholder="عنوان سایت..."
                                               value="{{ $option->get_option('title') }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>بسته پیشفرض </label>
                                        <select name="default_package" class="form-control">
                                            @foreach (\App\Models\Package::all() as $package)
                                                <option
                                                    value="{{ $package->id }}" {{ $option->get_option('default_package') == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>آستانه شارژ پیامک</label>
                                        <input type="text" name="min_sms_charge" class="form-control"
                                               placeholder="آستانه شارژ پیامک..."
                                               value="{{ $option->get_option('min_sms_charge') }}">
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label>اطلاعیه</label>
                                        <input type="text" name="notification-{{ app()->getLocale() }}" class="form-control"
                                               placeholder="اطلاعیه.."
                                               value="{{ $option->get_option('notification-' . app()->getLocale()) }}">
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label>امضای پیامک</label>
                                        <input type="text" name="sms_signiture_{{ app()->getLocale() }}" class="form-control"
                                               placeholder="امضای پیامک..."
                                               value="{{ $option->get_option('sms_signiture_'.app()->getLocale()) }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>قوانین</label>
                                        <textarea name="roles" class="form-control Reditor1"
                                                  placeholder="قوانین...">{{ $option->get_option('roles') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-success">ذخیره</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('js/ckeditor.js') }}"></script>
    <script>
        ClassicEditor.create(document.querySelector('.Reditor1'), {
            toolbar: {
                items: [
                    'undo', 'redo',
                    '|', 'heading',
                    '|', 'bold', 'italic',
                    '|', 'link', 'insertImage', 'insertTable', 'mediaEmbed',
                    '|', 'bulletedList', 'numberedList'
                ]
            },
            language: {
                // The UI will be Arabic.
                ui: 'fa',
                // And the content will be edited in Arabic.
                content: 'fa'
            }
        }).catch(error => {
            console.error(error);
        });
    </script>
@endsection
