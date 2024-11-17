@extends('parts.master')
@section('title', 'کاربران غیرفعال')
@section('styles')
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">فیلتر </h3>
                        </div>
                        <form>
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
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">روز</label>
                                        <input type="number" name="days" class="form-control"
                                               value="{{ old('days') ?? $request->days }}" placeholder="روز...">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success me-sm-3 me-1">فیلتر</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">کاربران غیر فعال ({{ price($records->total()) }})</h3>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            @if ($records->count() > 0)
                                <table class="table table-bordered table-hover m-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>مشترک</th>
                                        <th>تاریخ آخرین بازدید</th>
                                        <th>عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($records as $key=> $record)
                                        <tr>
                                            <td>{{ per_number($records->firstItem() + $key ) }}</td>
                                            <td>{{ $record->account?->center }}</td>
                                            <td>{{ persianTime($record->last_activity) }}</td>
                                            <td>
                                                <a target="_blank"
                                                   href="{{route('user.visitRecord',['account'=>$record->account_id])}}"
                                                   class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @if ($records->hasPages())
                                    <div class="d-flex mt-3">
                                        <div class="mx-auto">
                                            {{ $records->withQueryString()->links() }}
                                        </div>
                                    </div>
                                @endif
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
