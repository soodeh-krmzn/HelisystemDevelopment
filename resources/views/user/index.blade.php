@extends('parts.master')
@section('title', 'ایجاد کاربر')
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
                                ایجاد کاربر {{ $account != '' ? "اشتراک " . $account->getFullName() : '' }}
                            </h3>
                        </div>
                        <form action="{{ route('user.store') }}" method="POST">
                            <div class="card-body">
                                @csrf
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
                                        <input type="text" name="name" class="form-control" value="{{ old("name") }}" placeholder="نام...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>نام خانوادگی <span class="text-danger">*</span></label>
                                        <input type="text" name="family" class="form-control" value="{{ old("family") }}" placeholder="نام خانوادگی...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>موبایل <span class="text-danger">*</span></label>
                                        <input type="text" name="mobile" class="form-control" value="{{ old("mobile") }}" placeholder="موبایل..." maxlength="11">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>نام کاربری <span class="text-danger">*</span></label>
                                        <input type="text" name="username" class="form-control" value="{{ old("username") }}" placeholder="نام کاربری...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>رمز <span class="text-danger">*</span></label>
                                        <input type="text" name="password" class="form-control" value="{{ old("password") }}" placeholder="رمز...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>دسترسی <span class="text-danger">*</span></label>
                                        <select name="access" id="access" class="form-control">
                                            <option value="0" {{ old("access") == 0 ? "selected" : "" }}>محدود</option>
                                            <option value="1" {{ old("access") == 1 ? "selected" : "" }}>نامحدود</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($account == '')
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label>اشتراک</label>
                                            <select name="account_id" class="form-control">
                                                @foreach (\App\Models\Account::all() as $item)
                                                    <option value="{{ $item->id }}" {{ old("account_id") == $item->id ? "selected" : '' }}>{{ $item->getFullName() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="account_id" value="{{ $account->id }}">
                                @endif
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="reset" class="btn btn-secondary">انصراف</button>
                                        <button type="submit" class="btn btn-success">ذخیره</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                لیست کاربران {{ $account != '' ? "اشتراک " . $account->getFullName() : '' }}
                            </h3>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <div class="row">
                                <div class="col-md-12">
                                    @if ($users->count() > 0)
                                        <table class="table table-bordered table-hover m-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>نام</th>
                                                    <th>نام خانوادگی</th>
                                                    <th>نام کاربری</th>
                                                    <th>دسترسی</th>
                                                    <th>موبایل</th>
                                                    <th>وضعیت</th>
                                                    <th>علت</th>
                                                    <th>عملیات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->family }}</td>
                                                        <td>{{ $user->username }}</td>
                                                        <td>{{ $user->access == 0 ? "محدود" : "نامحدود" }}</td>
                                                        <td>{{ $user->mobile }}</td>
                                                        <td>@lang($user->status)</td>
                                                        <td>{{ $user->description }}</td>
                                                        <td>
                                                            <a href="{{ route('user.changePassword', $user->id) }}" class="btn btn-secondary btn-sm"><i class="fa fa-key"></i></a>
                                                            <a href="{{ route('user.edit', $user) }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>
                                                            <a href="{{ route('user.destroy', $user) }}" class="btn btn-danger btn-sm" data-confirm-delete="true"><i class="fa fa-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="alert alert-danger text-center m-2">موردی جهت نمایش موجود نیست.</div>
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
