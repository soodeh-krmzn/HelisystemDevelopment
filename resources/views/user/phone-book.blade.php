@extends('parts.master')
@section('title', 'دفترچه تلفن')
@section('styles')
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">مشترکین</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($accounts as $key => $grouped)
                                    @php
                                        $str = '';
                                        foreach ($grouped as $account) {
                                            $str .= $account->mobile . "\n";
                                        }
                                    @endphp
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="label">{{ $key }}</label>
                                            <textarea class="form-control text-left" onclick="this.select()" rows="10">{!! $str !!}</textarea>
                                        </div>
                                        {{ $grouped->count() }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">دیگر</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @php
                                    $str = "";
                                    foreach ($invited as $i) {
                                        $str .= $i->mobile . "\n";
                                    }
                                @endphp
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label">دعوت نامه</label>
                                        <textarea class="form-control text-left" onclick="this.select()" rows="10">{!! $str !!}</textarea>
                                    </div>
                                    {{ $invited->count() }}
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
