@extends('parts.master')
@section('title', 'تغییرات نرم افزار')
@section('styles')
    <style>
        .ck-editor__editable {
            height: 130px;
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
                            <h3 class="card-title">تغییر جدید </h3>
                        </div>
                        <form>
                            <input type="hidden" name="action"
                                   value="{{ $request->action == 'edit' ? 'update' : 'store' }}">
                            <input type="hidden" name="item" value="{{ $item?->id }}">
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
                                        <label class="form-label required">عنوان</label>
                                        <input type="text" name="title" value="{{ $item?->title }}"
                                               class="form-control" value="" required placeholder="عنوان...">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label class="form-label">وضعیت</label>

                                        <select name="status" class="custom-select" required>
                                            <option @selected($item?->status == 1) value="1">فعال</option>
                                            <option @selected($item?->status === 0) value="0">غیرفعال</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label class="form-label">نوع</label>

                                        <select name="type" class="custom-select" required>
                                            <option @selected($item?->type == 'update') value="update">update</option>
                                            <option @selected($item?->type == 'notification') value="notification">
                                                notification
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <textarea name="text" class="Reditor1">{{ $item?->text }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit"
                                                class="btn {{ $request->action == 'edit' ? 'btn-warning' : 'btn-success' }} me-sm-3 me-1">{{ $request->action == 'edit' ? 'ویرایش' : 'ثبت' }}</button>
                                        @if ($request->action == 'edit')
                                            <a href="{{ route('changeLogIndex') }}" class="btn btn-info">انصراف</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"> تغییرات ({{ number_format($changes->total()) }})</h3>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <?php
                            if ($changes->count() > 0) { ?>
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>عنوان</th>
                                    <th>متن</th>
                                    <th>وضعیت</th>
                                    <th>نوع</th>
                                    <th>تاریخ ثبت</th>
                                    <th>مدیریت</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($changes as $key => $change)
                                    <tr>
                                        <td>{{ per_number($changes->firstItem() + $key) }}</td>
                                        <td>{{ $change->title }}</td>
                                        <td style="max-width: 500px">
                                            {!! $change->text !!}
                                        </td>
                                        <td>
                                            <form>
                                                <input name="id" type="hidden" value="{{ $change->id }}">
                                                <select name='status' class="custom-select change-status"
                                                        data-id="{{ $change->id }}">
                                                    <option @selected($change->status == 1) value="1">فعال</option>
                                                    <option @selected($change->status == 0) value="0">غیرفعال</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>{{$change->type}}</td>
                                        <td>{{ persianTime($change->created_at) }}</td>
                                        <td>
                                            <a href="{{ route('changeLogIndex', ['action' => 'edit', 'item' => $change->id]) }}"
                                               class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                            <a href="{{ route('changeLogDelete', $change->id) }}"
                                               data-confirm-delete="true" class="btn btn-danger btn-sm"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @if ($changes->hasPages())
                                <div class="d-flex mt-3">
                                    <div class="mx-auto">
                                        {{ $changes->withQueryString()->links() }}
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
    <script src="{{ asset('js/RMain.js') }}"></script>
    <script>
        $('.change-status').on('change', function () {
            $(this).parent().submit();
        });
    </script>
@endsection
