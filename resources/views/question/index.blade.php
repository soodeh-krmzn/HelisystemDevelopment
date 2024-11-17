@extends('parts.master')
@section('title', 'سوالات متداول')
@section('styles')
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">سوالات متداول</h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <a href="{{ route('question.create') }}" class="btn ml-3 btn-success btn-sm">
                                        ایجاد سوال
                                    </a>
                                    <a href="{{ route('q-c.index') }}" class="btn btn-success btn-sm">
                                        بخش ها
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            @if ($questions->count() > 0)
                                <table class="table table-bordered table-hover m-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>بخش</th>
                                        <th>تیتر</th>
                                        <th>متن</th>
                                        <th>مدیریت</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($questions as $question)
                                        <tr>
                                            <td>{{ per_number($loop->index + 1) }}</td>
                                            <td>{{ $question->component?->name }}</td>
                                            <td>{{ $question->title }}</td>
                                            <td>{{ $question->body }}</td>
                                            <td>
                                                <a href="{{ route('question.create', ['item'=>$question->id]) }}"
                                                   class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>
                                                <a href="{{ route('question.destroy', $question->id) }}"
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
