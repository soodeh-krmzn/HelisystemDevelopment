@extends('parts.master')
@section('title', 'گزارش پرداخت ها')
@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts/dist/apexcharts.css">
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
                                    <div class="col-md-3">
                                        <label class="form-label">نوع پرداخت</label>
                                        <select name="payment_type" class="custom-select">
                                            <option value="">انتخاب کنید...</option>
                                            <option @selected($request->payment_type=='sms') value="sms">پیامک</option>
                                            <option @selected($request->payment_type=='account') value="account">شارژ اکانت</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">از تاریخ</label>
                                        <input id="from" type="text" name="from" class="form-control"
                                               value="{{ old('from') ?? $request->from }}" placeholder="از...">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">تا تاریخ</label>
                                        <input id="to" type="text" name="to" class="form-control"
                                               value="{{ old('to') ?? $request->to }}" placeholder="تا...">
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
                            <h3 class="card-title">نمودار گزارش پرداخت ها</h3>
                        </div>
                        <div class="card-body">
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);
            renderChart(chartData.labels, chartData.series);
        });

        function renderChart(labels, series) {
            var options = {
                series: series,
                chart: {
                    type: 'pie',
                    height: 350
                },
                labels: labels,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        }
    </script>
@endsection
