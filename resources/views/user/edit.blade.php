@extends('parts.master')
@section('title', 'ویرایش کاربر')
@section('styles')
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ویرایش کاربر {{ $user->getFullName() }}</h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <a href="{{ route('user.index') }}" class="btn btn-success btn-sm">بازگشت به لیست کاربران</a>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('user.update', $user) }}" method="POST">
                            <div class="card-body">
                                @csrf
                                @method('PUT')
                                @if ($errors->any())
                                    @foreach($errors->all() as $error)
                                        <div class="row">
                                            <div class="col-md-12 alert alert-danger">
                                                {{ $error }}
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>نام <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old("name") ?? $user->name }}" placeholder="نام...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>نام خانوادگی <span class="text-danger">*</span></label>
                                        <input type="text" name="family" class="form-control" value="{{ old("family") ?? $user->family }}" placeholder="نام خانوادگی...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>موبایل <span class="text-danger">*</span></label>
                                        <input type="text" name="mobile" class="form-control" value="{{ old("mobile") ?? $user->mobile }}" placeholder="موبایل..." maxlength="11">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>نام کاربری <span class="text-danger">*</span></label>
                                        <input type="text" name="username" class="form-control" value="{{ old("username") ?? $user->username }}" placeholder="نام کاربری...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>دسترسی <span class="text-danger">*</span></label>
                                        <select name="access" id="access" class="form-control">
                                            <option value="0" {{ old("access") == 0 ? "selected" : ($user->access == 0 ? "selected" : "") }}>محدود</option>
                                            <option value="1" {{ old("access") == 1 ? "selected" : ($user->access == 1 ? "selected" : "") }}>نامحدود</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>وضعیت <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control">
                                            <option @selected($user->status=='active') value="active">فعال</option>
                                            <option @selected($user->status=='deactive') value="deactive">غیرفعال</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>توکن عبور آفلاین</label>
                                        <input type="text" name="description" class="form-control" value="{{ old("description") ?? $user->description }}" placeholder="نام کاربری...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>علت</label>
                                        <input type="text" name="description" class="form-control" value="{{ old("description") ?? $user->description }}" placeholder="نام کاربری...">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button type="reset" class="btn btn-secondary">انصراف</button>
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
@endsection
