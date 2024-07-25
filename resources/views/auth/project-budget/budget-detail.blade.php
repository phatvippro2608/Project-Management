@extends('auth.main')
@section('head')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.51.0/apexcharts.min.js"></script>
@endsection
@section('contents')
    <div class="pagetitle">
        <h1>Project Budget</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Project Budget Summary</li>
                <li class="breadcrumb-item active">LIST OF EXPENSES</li>
            </ol>
        </nav>
    </div>

    <section class="sectionBudget">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Budget Estimates of Project: <b>{{$contingency_price->project_name}}</b></h3>
        <a href="{{route('budget.edit', $id) }}" class="btn btn-primary btn-sm w-25">Edit</a>
    </div>
    

        <h5>LIST OF EXPENSES</h5>
        <table class="table table-bordered">
            @php
                $sumOfTotal = 0;
            @endphp
            <tbody>
                <tr>
                    <td scope="row">SUBTOTAL</td>
                    <td id="sumOfTotal">0 VND</td>
                </tr>
                <tr>
                    <td scope="row">RISK (CONTINGENCY)</td>
                    <td>{{ number_format($contingency_price->project_price_contingency, 0, ',', ',')}} VND</td>
                </tr>
                <tr class="table-primary">
                    <td scope="row"><b>TOTAL</b></td>
                    <td id="allTotal"></td>
                </tr>
            </tbody>
        </table>
        <h5><b>Pie Chart Of Cost</b></h5>
        <div style="" >
            <div class="col-4"></div>
            <div class="col-8">
            <div id="pieChart" class="container-fluid"></div>
            </div>
        </div>
        

        <table class="table table-bordered">
            <thead>
                <th scope="row">DESCRIPTION</th>
                <th>LABOR QTY</th>
                <th>LABOR UNIT</th>
                <th>BUDGET QTY</th>
                <th>BUDGET UNIT</th>
                <th>LABOR COST</th>
                <th>MISC. COST</th>
                <th>OT BUDGET</th>
                <th>PER DIEM PAY</th>
                <th>SUBTOTAL</th>
                <th>REMARK</th>
            </thead>
            <tbody>
                @php
                    $costOfLabor = 0;
                    $costOfTravel = 0;
                    $costOfRenting = 0;
                    $costOfOther = 0;
                    $costOfBackOffice = 0;
                    $costOfCommission = 0;
                @endphp
                @foreach($dataCostGroup as $costGroup)
                @php
                    $total = 0;
                @endphp
                    <tr class="table-warning"><th colspan=11>{{$costGroup->project_cost_group_name}}</th></tr>
                    @foreach($dataCost as $cost)
                        @if($cost->project_id == $id && $cost->project_cost_group_id == $costGroup->project_cost_group_id)
                            @php
                                $costValue = $cost->project_cost_labor_qty * $cost->project_cost_budget_qty * ($cost->project_cost_labor_cost + $cost->project_cost_misc_cost + $cost->project_cost_ot_budget + $cost->project_cost_perdiempay);
                                $total += $costValue;
                                switch($cost->project_cost_group_id){
                                case 1:
                                    $costOfLabor = $total;
                                    break;
                                case 2:
                                    $costOfTravel = $total;
                                    break;
                                case 3:
                                    $costOfRenting = $total;
                                    break;
                                case 4:
                                    $costOfOther = $total;
                                    break;
                                case 5:
                                    $costOfBackOffice = $total;
                                    break;
                                case 6:
                                    $costOfCommission = $total;
                                    break;
                            }
                            @endphp
                            <tr>
                                <td>{{$cost->project_cost_description}}</td>
                                <td>{{$cost->project_cost_labor_qty}}</td>
                                <td>{{$cost->project_cost_labor_unit}}</td>
                                <td>{{$cost->project_cost_budget_qty}}</td>
                                <td>{{$cost->project_budget_unit}}</td>
                                <td>{{$cost->project_cost_labor_cost}}</td>
                                <td>{{$cost->project_cost_misc_cost}}</td>
                                <td>{{$cost->project_cost_ot_budget}}</td>
                                <td>{{$cost->project_cost_perdiempay}}</td>
                                <td>{{ number_format($costValue, 0, ',', ',') }} VND</td>
                                <td>{{$cost->project_cost_remaks}}</td>
                            </tr>
                        @endif
                    @endforeach
                    @php
                        $sumOfTotal += $total;
                    @endphp
                    <tr class="table-primary">
                        <td>SUBTOTAL</td>
                        <td colspan=8></td>
                        <td colspan=2><b>{{ number_format($total, 0, ',', ',') }} VND</b></td>
                    </tr>
                @endforeach
            </tbody>                
        </table>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var sumOfTotal = {{ $sumOfTotal }};
            var allTotal = {{$sumOfTotal + $contingency_price->project_price_contingency}}
            document.getElementById('sumOfTotal').innerHTML = new Intl.NumberFormat().format(sumOfTotal) + ' VND';
            document.getElementById('allTotal').innerHTML = '<span style="font-weight:bold;">' + new Intl.NumberFormat().format(allTotal) + ' VND</span>';
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            new ApexCharts(document.querySelector("#pieChart"), {
            series: [{{$costOfLabor}}, {{$costOfTravel}}, {{$costOfRenting}}, {{$costOfOther}}, {{$costOfBackOffice}}, {{$costOfCommission}}],
            chart: {
                height: 350,
                type: 'pie',
                toolbar: {
                show: true
                }
            },
            labels: ['Cost Of Labor', 'Cost Of Travel', 'Cost Of Renting', 'Cost Of Other', 'Cost Of BackOffice', 'Cost Of Commissision']
            }).render();
        });
    </script>
@endsection
