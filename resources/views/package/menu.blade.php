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
                            <h3 class="card-title">منو های بسته {{ $package->name }}</h3>
                        </div>
                        <form action="{{ route('package.menu.store', $package) }}" method="POST">
                            <div class="card-body">
                                @csrf
                                <div class="row">
                                    @foreach ($menus as $menu)
                                        <div class="col-6">
                                            <label>
                                                <input type="checkbox" name="menus[]" value="{{ $menu->id }}" {{ $package->menus->contains($menu->id) ? "checked" : '' }}>
                                                <strong><i class="fa fa-{{ $menu->icon }}"></i> {{ $menu->name }}</strong>
                                            </label>
                                            {{ $menu->childrenListPackage($package) }}
                                        </div>
                                    @endforeach
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
