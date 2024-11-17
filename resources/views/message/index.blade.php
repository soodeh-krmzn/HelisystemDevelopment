@extends('parts.master')
@section('title', 'تاریخچه ارسال پیامک')
@section('styles')
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">تاریخچه ارسال پیامک</h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <a href="{{ route('message.create') }}" class="btn btn-success btn-sm">
                                        ارسال پیامک
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive">

                            @if ($messages->count() > 0)
                                <table class="table table-bordered table-hover m-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>کد اشتراک</th>
                                        <th>نام</th>
                                        <th>نام خانوادگی</th>
                                        <th>موبایل</th>
                                        <th>تاریخ و ساعت</th>
                                        <th>متن پیام</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($messages as $message)
                                        <tr>
                                            <td>{{ per_number($loop->index + 1) }}</td>
                                            <td>{{ $message->account_id }}</td>
                                            <td>{{ $message->name }}</td>
                                            <td>{{ $message->family }}</td>
                                            <td>{{ $message->mobile }}</td>
                                            <td>{{ per_number($message->created_at ? Verta($message->created_at)->format("Y/m/d H:i:s") : '') }}</td>
                                            <td>{{ $message->text }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-danger m-2 text-center">موردی جهت نمایش موجود نیست.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@endsection
