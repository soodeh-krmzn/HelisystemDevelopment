@extends('parts.master')
@section('title', 'بخش های سوالات متداول')
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
                                ایجاد بخش
                            </h3>
                        </div>
                        <form action="{{ route('q-c.store') }}" method="POST">
                            <div class="card-body">
                                @csrf
                                <input type="hidden" name="action" value="{{ $action }}">
                                <input type="hidden" name="item" value="{{$item?->id }}">
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
                                        <label>نام <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name')??$item?->name }}" placeholder="نام...">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>توضیحات </label>
                                        <input type="text" name="desc" class="form-control"
                                            value="{{ old('desc')??$item?->desc }}" placeholder="توضیحات...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="invisible d-md-block d-none">عملیات</label>
                                        <a href="{{route('q-c.index')}}" class="btn btn-secondary">انصراف</a>
                                        <button type="submit" class="btn btn-success">{{$action=='store'?'ذخیره':'ویرایش'}}</button>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="card-footer">
                                <div class="row">

                                </div>
                            </div> --}}
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                لیست بسته های تعرفه
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    @if ($components->count() > 0)
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>نام</th>
                                                    <th>توضیحات</th>
                                                    <th>عملیات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($components as $component)
                                                    <tr>
                                                        <td>{{ per_number($loop->index + 1) }}</td>
                                                        <td>{{ $component->name }}</td>
                                                        <td>{{ $component->description }}</td>
                                                        <td>
                                                            <a href="{{ route('q-c.index',['item'=>$component->id]) }}"
                                                                class="btn btn-warning btn-sm"><i
                                                                    class="fa fa-pencil"></i></a>
                                                            <a href="{{route('q-c.delete',$component->id)}}" data-confirm-delete="true" class="btn btn-danger btn-sm"><i
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
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@endsection
