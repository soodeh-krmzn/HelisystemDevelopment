@extends('parts.master')
@section('title', 'نمایش تیکت')
@section('styles')
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <div class="card px-4">
                        <div class="card-header mb-3">
                            <div class="row">
                                <div class="col-md-8 row">
                                    <h3 class="card-title col-12">{{ $ticket->subject }}</h3>
                                    <div class="col-12">
                                        <div class="col">
                                            <ul>
                                                <li>تاریخ ثبت: {{ persianTime($ticket->created_at) }}</li>
                                                <li> آخرین پیام: {{ $ticket->lastMsgTime() }}</li>
                                            </ul>
                                        </div>
                                        <div class="col">
                                            <ul>
                                                <li>وضعیت: @lang($ticket->status)</li>
                                                <li> نام مجموعه: <a target="_blanck" href="https://helionline.ir/login-as/Ux2kC5tptbhGO8KTGsc/{{ $ticket->account_id }}"> {{$ticket->account->center }} </a> </li>
                                                <li>ارجاعات: {!! $ticket->referencesUser() !!} </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 row">

                                    <div class="col">
                                        <div class="form-group">
                                            <form id="status-form">
                                                <label for="inputState" class="form-label">وضعیت</label>
                                                <select name="status" id="status-select" class="custom-select">
                                                    <option value="">انتخاب کنید...</option>
                                                    <option value="in-progress" @selected($ticket->status=='checking') >
                                                        درحال بررسی
                                                    </option>
                                                    <option value="closed" @selected($ticket->status=='close')>بستن
                                                    </option>
                                                </select>
                                            </form>
                                        </div>
                                        <div class="form-group">
                                            <form action="">
                                                <label for="inputState2" class="form-label">ارجاع به</label>
                                                <select name="ref" id="inputState2" class="custom-select" required>
                                                    <option value="">انتخاب کنید...</option>
                                                    @foreach ($admins as $admin)
                                                        @if ($admin->id != auth()->id())
                                                            <option value="{{ $admin->id }}">{{ $admin->getFullName() }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-warning mt-1">ارجاع</button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="card-body p-0 table-responsive"
                             style="
                        max-height: 800px;
                        overflow-y: scroll;">
                            @foreach ($ticket->chats as $chat)
                                @if ($chat->account_id == 0)
                                    <div class="card card-warning" style=" margin-left: 20%;">
                                        <div class="card-header">
                                            <span> {{ $chat->admin->getFullName() }}</span>
                                            @if ($file = $chat->getFile())
                                                <a href="{{ $file }}" target="_blank"
                                                   class="btn btn-primary float-left">
                                                    <i class="fa fa-download me-1"></i> فایل
                                                </a>
                                            @endif
                                        </div>
                                        <div class="card-body">{!! $chat->body !!}</div>
                                        <div class="card-footer">
                                            {{ persianTime($chat->created_at) }}
                                        </div>
                                    </div>
                                @else
                                    <div class="card card-info" style=" margin-right: 20%;">
                                        <div class="card-header">
                                            <span> {{ $chat->user->getFullName() }}</span>
                                            @if ($file = $chat->getFile())
                                                <a href="{{ $file }}" target="_blank"
                                                   class="btn btn-primary float-left">
                                                    <i class="fa fa-download me-1"></i> فایل
                                                </a>
                                            @endif
                                        </div>
                                        <div class="card-body">{!! $chat->body !!}</div>
                                        <div class="card-footer">
                                            {{ persianTime($chat->created_at) }}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="card">
                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h3 class="card-title">پاسخ</h3>
                            </div>
                            <div class="card-body">
                                <textarea name="body" class="form-control Reditor1" rows="25"></textarea>
                                <input type="file" name="file">
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success">ارسال</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $('#status-select').on('change', function () {
            $('#status-form').submit();
        })
    </script>
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
