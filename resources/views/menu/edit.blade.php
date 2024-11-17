@extends('parts.master')
@section('title', 'میز کار')
@section('styles')
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            @include('parts.error')
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('menu.update', $menu) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">ویرایش منو {{ $menu->name }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">صفحه والد</label>
                                        <select name="parent_id" class="form-control">
                                            <option value="0">بدون والد</option>
                                            @foreach($menus as $item)
                                                <option
                                                    {{ ($item->id == old("parent_id")) ? 'selected' : ($menu->parent_id == $item->id ? 'selected' : '') }} value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">برچسب <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="label" class="form-control" placeholder="برچسب..."
                                               value="{{ old("label") ?? $menu->name }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">آیکن <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="icon" class="form-control" placeholder="آیکن..."
                                               value="{{ old("icon") ?? $menu->icon }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">آدرس <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="url" class="form-control" style="direction: ltr;"
                                               placeholder="Route..." value="{{ old("url") ?? $menu->url }}">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">آدرس ویدئو آموزشی</label>
                                        <input type="text" name="learn_url" class="form-control"
                                               placeholder="آدرس ویدئو آموزشی..."
                                               value="{{ old("learn_url") ?? $menu->learn_url }}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">ترتیب نمایش</label>
                                        <input type="text" name="display_order" class="form-control"
                                               placeholder="ترتیب نمایش..."
                                               value="{{ old("display_order") ?? $menu->display_order }}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label required">نمایش در فهرست</label>
                                        <select name="display_nav" class="form-control">
                                            <option value="1" {{ $menu->display_nav == 1 ? 'selected' : '' }}>بله
                                            </option>
                                            <option value="0" {{ $menu->display_nav == 0 ? 'selected' : '' }}>خیر
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12 form-group">
                                        <label class="form-label required">توضیحات</label>
                                        <input type="text" name="details" class="form-control" placeholder="توضیحات..."
                                               value="{{ old("details") ?? $menu->details }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button type="reset" class="btn btn-secondary">انصراف</button>
                                <button type="submit" class="btn btn-success">ذخیره</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@endsection
