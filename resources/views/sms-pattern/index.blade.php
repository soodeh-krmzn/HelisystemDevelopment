@extends('parts.master')
@section('title', 'الگوهای پیامک')
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
                                لیست الگوی پیامک
                            </h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <a href="{{ route('sms-pattern.create') }}" class="btn btn-success btn-sm">
                                        ایجاد الگوی پیامک
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            @if ($smsPatterns->count() > 0)
                                <table class="table table-bordered table-hover m-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>دسته</th>
                                        <th>نام</th>
                                        <th>متن پیامک</th>
                                        <th>صفحه</th>
                                        <th>هزینه</th>
                                        <th>مدیریت</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($smsPatterns as $pattern)
                                        <tr>
                                            <td>{{ per_number($loop->index + 1) }}</td>
                                            <td>{{ $pattern->category?->name }}</td>
                                            <td>{{ $pattern->name }}</td>
                                            <td>{{ $pattern->text }}</td>
                                            <td>{{ per_number($pattern->page) }}</td>
                                            <td>{{ per_number(number_format($pattern->cost)) }}</td>
                                            <td>
                                                <a href="{{ route('sms-pattern.edit', $pattern) }}"
                                                   class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>
                                                <a href="{{ route('sms-pattern.destroy', $pattern) }}"
                                                   class="btn btn-danger btn-sm" data-confirm-delete="true"><i
                                                        class="fa fa-trash"></i></a>
                                            </td>
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
