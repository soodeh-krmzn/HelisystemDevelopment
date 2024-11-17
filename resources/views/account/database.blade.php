@extends('parts.master')
@section('title', 'میز کار')
@section('styles')
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ویرایش اشتراک {{ $account->getFullName() }}</h3>
                        </div>
                        <form action="{{ route('account.storeDatabase', $account) }}" method="POST">
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
                                        <label>نام پایگاه داده</label>
                                        <input type="text" name="db_name" class="form-control" readonly value="{{ old("db_name")??$account->id."db"}}" placeholder="نام پایگاه داده...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>نام کاربری پایگاه داده</label>
                                        <input type="text" name="db_user" class="form-control" readonly value="{{ old("db_user")??$account->id."user" }}" placeholder="نام کاربری پایگاه داده...">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>رمز پایگاه داده</label>
                                        <input type="text" name="db_pass" class="form-control" value="{{\Illuminate\Support\Str::random(12) }}" placeholder="رمز پایگاه داده...">
                                    </div>
                                </div>
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
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@endsection
