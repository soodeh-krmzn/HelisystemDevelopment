@extends('parts.master')
@section('title', 'منوها')
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
                                لیست منوها
                            </h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <a href="{{ route('menu.create') }}" class="btn btn-success btn-sm">
                                        ایجاد منو
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            @if ($menus->count() > 0)
                                <table class="table table-bordered table-hover m-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>صفحه والد</th>
                                        <th>برچسب</th>
                                        <th>آیکن</th>
                                        <th>آدرس</th>
                                        <th>آدرس ویدئو آموزشی</th>
                                        <th>توضیحات</th>
                                        <th>فهرست</th>
                                        <th>ترتیب نمایش</th>
                                        <th>مدیریت</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($menus as $menu)
                                        <tr>
                                            <td>{{ per_number($loop->index + 1) }}</td>
                                            <td>{{ $menu->parent?->name }}</td>
                                            <td>{{ $menu->name }}</td>
                                            <td>
                                                <i class="fa fa-{{ $menu->icon }}"></i>
                                            </td>
                                            <td>{{ $menu->url }}</td>
                                            <td>{{ $menu->learn_url }}</td>
                                            <td>{{ $menu->details }}</td>
                                            <td>{!! ($menu->display_nav == 1) ? "<span class='badge bg-success'>بله</span>" : "<span class='badge bg-danger'>خیر</span>" !!}</td>
                                            <td>{{ per_number($menu->display_order) }}</td>
                                            <td>
                                                <a href="{{ route('menu.edit', $menu) }}"
                                                   class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>
                                                <a href="{{ route('menu.destroy', $menu) }}"
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
