@extends("rrg.layout")

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <section class="content-header">
                <h1>Tree Monitoring Dashboard</h1>
                <br>
            </section>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?= $total ?></h3>
                            <p>Total Planted trees</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-tree"></i>
                        </div>
                        <a class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3><?= $srate ?>%</h3>
                            <p>Survival Rate</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-pie-chart"></i>
                        </div>
                        <a class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="clearfix visible-sm-block"></div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3><?= $mrate ?>%</h3>
                            <p>Mortality rate</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-pie-chart"></i>
                        </div>
                        <a class="small-box-footer">
                            More info
                            <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 col-sm-2 text-center">
                    <span class="info-box-text">
                        Total Recorded Sites</span>
                    <span class="info-box-number">
                        <?= $tsite ?>
                    </span>
                </div>
                <div class="col-sm-4 col-sm-2 text-center">
                    <span class="info-box-text">Avg. tree DBH</span>
                    <span class="info-box-number">
                        <?= $avgDBH ?> cm
                    </span>
                </div>
                <div class="col-sm-4 col-sm-2 text-center"><span class="info-box-text">Avg. Total Tree Height</span>
                    <span class="info-box-number">
                        <?= $avgHeight ?> m
                    </span>
                </div>
                <div class="col-sm-4 col-sm-2 text-center">
                    <span class="info-box-text">Total Biomass</span>
                    <span class="info-box-number">
                        <?= $tAgb ?> kg
                </div>
                <div class="col-sm-4 col-sm-2 text-center">
                    <span class="info-box-text">Total Carbon
                        Stored</span>
                    <span class="info-box-number">
                        <?= $tc ?> kg
                    </span>
                </div>
                <div class="col-sm-4 col-sm-2 text-center">
                    <span class="info-box-text">Total
                        CO<sub>2</sub> Stored
                    </span>
                    <span class="info-box-number">
                        <?= $tco2 ?> kg
                    </span>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-8">
                    <div class="nav-tabs-custom">
                        <!-- Tabs within a box -->
                        <ul class="nav nav-tabs pull-right ui-sortable-handle">
                            <li class="tab active">
                                <a href="#planting" data-toggle="tab" aria-expanded="true">
                                    Planting
                                </a>
                            </li>
                            <li class="tab">
                                <a href="#monitoring" data-toggle="tab" aria-expanded="false">
                                    Monitoring
                                </a>
                            </li>
                            <li class="pull-left header">
                                <div style="display:flex;justify-content:center;align-items:center">
                                    Activity
                                </div>
                            </li>
                        </ul>
                        <div class="tab-content no-padding">
                            <div class="chart tab-pane active" id="planting" style="max-height: 320px;">
                                <canvas id="pChart" style="padding:10px;height:320px"></canvas>
                            </div>
                            <div class="chart tab-pane" id="monitoring" style=" max-height: 320px;">
                                <canvas id="mChart" style="padding:10px;height:320px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                Planted Trees per Site
                                <a style="margin-left:5px;" title="Show table view">
                                    <i class="fa fa-info-circle"></i>
                                </a>
                            </h3>
                        </div>
                        <div class="box-body" style="justify-content:center;align-items:center;display:flex;">
                            <canvas id="pieChart" style="min-height:280px"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Top 10 dominant tree species
                                <a style="margin-left:5px;" title="Show table view">
                                    <i class="fa fa-info-circle"></i>
                                </a>
                            </h3>
                            <div class="box-body">
                                <div class="chart"><canvas id="barChart" style="height:300px"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection