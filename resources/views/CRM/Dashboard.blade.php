@extends('CRM.Layout.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <div class="col-xxl-8 mb-6 order-0">
                <div class="card">
                    <div class="d-flex align-items-start row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">Congratulations John! 🎉</h5>
                                <p class="mb-6">You have done 72% more sales today.<br>Check your new badge in your profile.
                                </p>

                                <a href="javascript:;" class="btn btn-sm btn-label-primary">View Badges</a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-6">
                                <img src="../../assets/img/illustrations/man-with-laptop.png" height="175"
                                    class="scaleX-n1-rtl" alt="View Badge User">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-lg-12 col-md-4 order-1">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-6 mb-6">
                        <div class="card h-100">
                            <div class="card-body pb-4">
                                <span class="d-block fw-medium mb-1">Order</span>
                                <h4 class="card-title mb-0">276k</h4>
                            </div>
                            <div id="orderChart" class="pb-3 pe-1" style="min-height: 80px;">
                                <div id="apexcharts5vi3nkcyj"
                                    class="apexcharts-canvas apexcharts5vi3nkcyj apexcharts-theme-"
                                    style="width: 459px; height: 80px;"><svg xmlns="http://www.w3.org/2000/svg"
                                        version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg"
                                        xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="459" height="80">
                                        <foreignObject x="0" y="0" width="459" height="80">
                                            <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml"
                                                style="max-height: 40px;"></div>
                                            <style type="text/css">
                                                .apexcharts-flip-y {
                                                    transform: scaleY(-1) translateY(-100%);
                                                    transform-origin: top;
                                                    transform-box: fill-box;
                                                }

                                                .apexcharts-flip-x {
                                                    transform: scaleX(-1);
                                                    transform-origin: center;
                                                    transform-box: fill-box;
                                                }

                                                .apexcharts-legend {
                                                    display: flex;
                                                    overflow: auto;
                                                    padding: 0 10px;
                                                }

                                                .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                    flex-direction: column;
                                                }

                                                .apexcharts-legend-group {
                                                    display: flex;
                                                }

                                                .apexcharts-legend-group-vertical {
                                                    flex-direction: column-reverse;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom,
                                                .apexcharts-legend.apx-legend-position-top {
                                                    flex-wrap: wrap
                                                }

                                                .apexcharts-legend.apx-legend-position-right,
                                                .apexcharts-legend.apx-legend-position-left {
                                                    flex-direction: column;
                                                    bottom: 0;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                .apexcharts-legend.apx-legend-position-right,
                                                .apexcharts-legend.apx-legend-position-left {
                                                    justify-content: flex-start;
                                                    align-items: flex-start;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                    justify-content: center;
                                                    align-items: center;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                    justify-content: flex-end;
                                                    align-items: flex-end;
                                                }

                                                .apexcharts-legend-series {
                                                    cursor: pointer;
                                                    line-height: normal;
                                                    display: flex;
                                                    align-items: center;
                                                }

                                                .apexcharts-legend-text {
                                                    position: relative;
                                                    font-size: 14px;
                                                }

                                                .apexcharts-legend-text *,
                                                .apexcharts-legend-marker * {
                                                    pointer-events: none;
                                                }

                                                .apexcharts-legend-marker {
                                                    position: relative;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    cursor: pointer;
                                                    margin-right: 1px;
                                                }

                                                .apexcharts-legend-series.apexcharts-no-click {
                                                    cursor: auto;
                                                }

                                                .apexcharts-legend .apexcharts-hidden-zero-series,
                                                .apexcharts-legend .apexcharts-hidden-null-series {
                                                    display: none !important;
                                                }

                                                .apexcharts-inactive-legend {
                                                    opacity: 0.45;
                                                }
                                            </style>
                                        </foreignObject>
                                        <rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0"
                                            stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
                                        <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                        <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                        <g class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g>
                                        <g class="apexcharts-inner apexcharts-graphical"
                                            transform="translate(4.666666666666667, 15)">
                                            <defs>
                                                <clipPath id="gridRectMask5vi3nkcyj">
                                                    <rect width="447.3333333333333" height="60.333333333333336" x="0" y="0"
                                                        rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                                        stroke-dasharray="0" fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="gridRectBarMask5vi3nkcyj">
                                                    <rect width="453.3333333333333" height="66.33333333333334" x="-3" y="-3"
                                                        rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                                        stroke-dasharray="0" fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="gridRectMarkerMask5vi3nkcyj">
                                                    <rect width="461.3333333333333" height="74.33333333333334" x="-7" y="-7"
                                                        rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                                        stroke-dasharray="0" fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="forecastMask5vi3nkcyj"></clipPath>
                                                <clipPath id="nonForecastMask5vi3nkcyj"></clipPath>
                                                <linearGradient x1="0" y1="0" x2="0" y2="1" id="SvgjsLinearGradient1053">
                                                    <stop stop-opacity="0.4" stop-color="var(--bs-success)" offset="0">
                                                    </stop>
                                                    <stop stop-opacity="0.4" stop-color="var(--bs-paper-bg)" offset="1">
                                                    </stop>
                                                    <stop stop-opacity="0.4" stop-color="var(--bs-paper-bg)" offset="1">
                                                    </stop>
                                                </linearGradient>
                                            </defs>
                                            <line x1="0" y1="0" x2="0" y2="60.333333333333336" stroke="#b6b6b6"
                                                stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs"
                                                x="0" y="0" width="1" height="60.333333333333336" fill="#b1b9c4"
                                                filter="none" fill-opacity="0.9" stroke-width="1"></line>
                                            <g class="apexcharts-grid">
                                                <g class="apexcharts-gridlines-horizontal" style="display: none;">
                                                    <line x1="0" y1="0" x2="447.3333333333333" y2="0" stroke="#e0e0e0"
                                                        stroke-dasharray="0" stroke-linecap="butt"
                                                        class="apexcharts-gridline"></line>
                                                    <line x1="0" y1="30.166666666666668" x2="447.3333333333333"
                                                        y2="30.166666666666668" stroke="#e0e0e0" stroke-dasharray="0"
                                                        stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                    <line x1="0" y1="60.333333333333336" x2="447.3333333333333"
                                                        y2="60.333333333333336" stroke="#e0e0e0" stroke-dasharray="0"
                                                        stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                </g>
                                                <g class="apexcharts-gridlines-vertical" style="display: none;"></g>
                                                <line x1="0" y1="60.333333333333336" x2="447.3333333333333"
                                                    y2="60.333333333333336" stroke="transparent" stroke-dasharray="0"
                                                    stroke-linecap="butt"></line>
                                                <line x1="0" y1="1" x2="0" y2="60.333333333333336" stroke="transparent"
                                                    stroke-dasharray="0" stroke-linecap="butt"></line>
                                            </g>
                                            <g class="apexcharts-grid-borders" style="display: none;"></g>
                                            <g class="apexcharts-area-series apexcharts-plot-series">
                                                <g class="apexcharts-series" zIndex="0" seriesName="series-1"
                                                    data:longestSeries="true" rel="1" data:realIndex="0">
                                                    <path
                                                        d="M 0 36.2C 26.094444444444445 36.2 48.46111111111111 37.708333333333336 74.55555555555556 37.708333333333336C 100.65 37.708333333333336 123.01666666666667 7.541666666666671 149.11111111111111 7.541666666666671C 175.20555555555555 7.541666666666671 197.57222222222222 48.266666666666666 223.66666666666666 48.266666666666666C 249.76111111111112 48.266666666666666 272.1277777777778 28.65833333333333 298.22222222222223 28.65833333333333C 324.31666666666666 28.65833333333333 346.68333333333334 33.18333333333333 372.77777777777777 33.18333333333333C 398.8722222222222 33.18333333333333 421.2388888888889 1.5083333333333258 447.3333333333333 1.5083333333333258C 447.3333333333333 1.5083333333333258 447.3333333333333 1.5083333333333258 447.3333333333333 60.333333333333336 L 0 60.333333333333336z"
                                                        fill="url(#SvgjsLinearGradient1053)" fill-opacity="1" stroke="none"
                                                        stroke-opacity="1" stroke-linecap="butt" stroke-width="0"
                                                        stroke-dasharray="0" class="apexcharts-area" index="0"
                                                        clip-path="url(#gridRectMask5vi3nkcyj)"
                                                        pathTo="M 0 36.2C 26.094444444444445 36.2 48.46111111111111 37.708333333333336 74.55555555555556 37.708333333333336C 100.65 37.708333333333336 123.01666666666667 7.541666666666671 149.11111111111111 7.541666666666671C 175.20555555555555 7.541666666666671 197.57222222222222 48.266666666666666 223.66666666666666 48.266666666666666C 249.76111111111112 48.266666666666666 272.1277777777778 28.65833333333333 298.22222222222223 28.65833333333333C 324.31666666666666 28.65833333333333 346.68333333333334 33.18333333333333 372.77777777777777 33.18333333333333C 398.8722222222222 33.18333333333333 421.2388888888889 1.5083333333333258 447.3333333333333 1.5083333333333258C 447.3333333333333 1.5083333333333258 447.3333333333333 1.5083333333333258 447.3333333333333 60.333333333333336 L 0 60.333333333333336z"
                                                        pathFrom="M 0 60.333333333333336 L 0 60.333333333333336 L 74.55555555555556 60.333333333333336 L 149.11111111111111 60.333333333333336 L 223.66666666666666 60.333333333333336 L 298.22222222222223 60.333333333333336 L 372.77777777777777 60.333333333333336 L 447.3333333333333 60.333333333333336z">
                                                    </path>
                                                    <path
                                                        d="M 0 36.2C 26.094444444444445 36.2 48.46111111111111 37.708333333333336 74.55555555555556 37.708333333333336C 100.65 37.708333333333336 123.01666666666667 7.541666666666671 149.11111111111111 7.541666666666671C 175.20555555555555 7.541666666666671 197.57222222222222 48.266666666666666 223.66666666666666 48.266666666666666C 249.76111111111112 48.266666666666666 272.1277777777778 28.65833333333333 298.22222222222223 28.65833333333333C 324.31666666666666 28.65833333333333 346.68333333333334 33.18333333333333 372.77777777777777 33.18333333333333C 398.8722222222222 33.18333333333333 421.2388888888889 1.5083333333333258 447.3333333333333 1.5083333333333258"
                                                        fill="none" fill-opacity="1" stroke="var(--bs-success)"
                                                        stroke-opacity="1" stroke-linecap="butt" stroke-width="2"
                                                        stroke-dasharray="0" class="apexcharts-area" index="0"
                                                        clip-path="url(#gridRectMask5vi3nkcyj)"
                                                        pathTo="M 0 36.2C 26.094444444444445 36.2 48.46111111111111 37.708333333333336 74.55555555555556 37.708333333333336C 100.65 37.708333333333336 123.01666666666667 7.541666666666671 149.11111111111111 7.541666666666671C 175.20555555555555 7.541666666666671 197.57222222222222 48.266666666666666 223.66666666666666 48.266666666666666C 249.76111111111112 48.266666666666666 272.1277777777778 28.65833333333333 298.22222222222223 28.65833333333333C 324.31666666666666 28.65833333333333 346.68333333333334 33.18333333333333 372.77777777777777 33.18333333333333C 398.8722222222222 33.18333333333333 421.2388888888889 1.5083333333333258 447.3333333333333 1.5083333333333258"
                                                        pathFrom="M 0 60.333333333333336 L 0 60.333333333333336 L 74.55555555555556 60.333333333333336 L 149.11111111111111 60.333333333333336 L 223.66666666666666 60.333333333333336 L 298.22222222222223 60.333333333333336 L 372.77777777777777 60.333333333333336 L 447.3333333333333 60.333333333333336"
                                                        fill-rule="evenodd"></path>
                                                    <g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown"
                                                        data:realIndex="0">
                                                        <g class="" clip-path="url(#gridRectMarkerMask5vi3nkcyj)">
                                                            <path d="M -1, 36.2 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="transparent" fill-opacity="1" stroke="transparent" stroke-opacity="0.9"
                                                                stroke-linecap="butt" stroke-width="4" stroke-dasharray="0"
                                                                cx="-1" cy="36.2" shape="circle"
                                                                class="apexcharts-marker no-pointer-events w74baf372h"
                                                                rel="0" j="0" index="0" default-marker-size="6"></path>
                                                            <path d="M 73.55555555555556, 37.708333333333336 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="transparent" fill-opacity="1" stroke="transparent" stroke-opacity="0.9"
                                                                stroke-linecap="butt" stroke-width="4" stroke-dasharray="0"
                                                                cx="73.55555555555556" cy="37.708333333333336"
                                                                shape="circle"
                                                                class="apexcharts-marker no-pointer-events wf03q10mg"
                                                                rel="1" j="1" index="0" default-marker-size="6"></path>
                                                        </g>
                                                        <g class="" clip-path="url(#gridRectMarkerMask5vi3nkcyj)">
                                                            <path d="M 148.11111111111111, 7.541666666666671 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="transparent" fill-opacity="1" stroke="transparent" stroke-opacity="0.9"
                                                                stroke-linecap="butt" stroke-width="4" stroke-dasharray="0"
                                                                cx="148.11111111111111" cy="7.541666666666671"
                                                                shape="circle"
                                                                class="apexcharts-marker no-pointer-events wvyf5k631"
                                                                rel="2" j="2" index="0" default-marker-size="6"></path>
                                                        </g>
                                                        <g class="" clip-path="url(#gridRectMarkerMask5vi3nkcyj)">
                                                            <path d="M 222.66666666666666, 48.266666666666666 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="transparent" fill-opacity="1" stroke="transparent" stroke-opacity="0.9"
                                                                stroke-linecap="butt" stroke-width="4" stroke-dasharray="0"
                                                                cx="222.66666666666666" cy="48.266666666666666"
                                                                shape="circle"
                                                                class="apexcharts-marker no-pointer-events wv2zavhxz"
                                                                rel="3" j="3" index="0" default-marker-size="6"></path>
                                                        </g>
                                                        <g class="" clip-path="url(#gridRectMarkerMask5vi3nkcyj)">
                                                            <path d="M 297.22222222222223, 28.65833333333333 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="transparent" fill-opacity="1" stroke="transparent" stroke-opacity="0.9"
                                                                stroke-linecap="butt" stroke-width="4" stroke-dasharray="0"
                                                                cx="297.22222222222223" cy="28.65833333333333"
                                                                shape="circle"
                                                                class="apexcharts-marker no-pointer-events wvrdz5r6r"
                                                                rel="4" j="4" index="0" default-marker-size="6"></path>
                                                        </g>
                                                        <g class="" clip-path="url(#gridRectMarkerMask5vi3nkcyj)">
                                                            <path d="M 371.77777777777777, 33.18333333333333 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="transparent" fill-opacity="1" stroke="transparent" stroke-opacity="0.9"
                                                                stroke-linecap="butt" stroke-width="4" stroke-dasharray="0"
                                                                cx="371.77777777777777" cy="33.18333333333333"
                                                                shape="circle"
                                                                class="apexcharts-marker no-pointer-events wfuiv0zo1"
                                                                rel="5" j="5" index="0" default-marker-size="6"></path>
                                                        </g>
                                                        <g class="" clip-path="url(#gridRectMarkerMask5vi3nkcyj)">
                                                            <path d="M 446.3333333333333, 1.5083333333333258 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="var(--bs-paper-bg)" fill-opacity="1" stroke="var(--bs-success)"
                                                                stroke-opacity="0.9" stroke-linecap="butt" stroke-width="4"
                                                                stroke-dasharray="0" cx="446.3333333333333"
                                                                cy="1.5083333333333258" shape="circle"
                                                                class="apexcharts-marker no-pointer-events wyiwu9ahj"
                                                                rel="6" j="6" index="0" default-marker-size="6"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                                <g class="apexcharts-datalabels" data:realIndex="0"></g>
                                            </g>
                                            <line x1="0" y1="0" x2="447.3333333333333" y2="0" stroke="#b6b6b6"
                                                stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                                class="apexcharts-ycrosshairs"></line>
                                            <line x1="0" y1="0" x2="447.3333333333333" y2="0" stroke="#b6b6b6"
                                                stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                                class="apexcharts-ycrosshairs-hidden"></line>
                                            <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                <g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g>
                                            </g>
                                            <g class="apexcharts-yaxis-annotations"></g>
                                            <g class="apexcharts-xaxis-annotations"></g>
                                            <g class="apexcharts-point-annotations"></g>
                                        </g>
                                    </svg>
                                    <div class="apexcharts-tooltip apexcharts-theme-light">
                                        <div class="apexcharts-tooltip-title"
                                            style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                        <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                            style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                style="background-color: var(--bs-success);"></span>
                                            <div class="apexcharts-tooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                        <div class="apexcharts-yaxistooltip-text"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                    <div class="avatar flex-shrink-0">
                                        <img src="../../assets/img/icons/unicons/wallet-info.png" alt="wallet info"
                                            class="rounded">
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-base bx bx-dots-vertical-rounded text-body-secondary"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-1">Sales</p>
                                <h4 class="card-title mb-3">$4,679</h4>
                                <small class="text-success fw-medium"><i class="icon-base bx bx-up-arrow-alt"></i>
                                    +28.42%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Total Revenue -->
            <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-lg-8">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="card-title mb-0">
                                    <h5 class="m-0 me-2">Total Revenue</h5>
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="totalRevenue" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-base bx bx-dots-vertical-rounded icon-lg text-body-secondary"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalRevenue">
                                        <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                    </div>
                                </div>
                            </div>
                            <div id="totalRevenueChart" class="px-3" style="min-height: 315px;">
                                <div id="apexchartsafaje2rv" class="apexcharts-canvas apexchartsafaje2rv apexcharts-theme-"
                                    style="width: 611px; height: 300px;"><svg xmlns="http://www.w3.org/2000/svg"
                                        version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg"
                                        xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="611" height="300">
                                        <foreignObject x="0" y="0" width="611" height="300">
                                            <div class="apexcharts-legend apexcharts-align-left apx-legend-position-top"
                                                xmlns="http://www.w3.org/1999/xhtml"
                                                style="right: 0px; position: absolute; left: 0px; top: 4px; max-height: 150px;">
                                                <div class="apexcharts-legend-series" rel="1" seriesname="2024"
                                                    data:collapsed="false" style="margin: 4px 10px;"><span
                                                        class="apexcharts-legend-marker" rel="1" data:collapsed="false"
                                                        style="height: 8px; width: 8px; left: 0px; top: 0px;"><svg
                                                            xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="100%"
                                                            height="100%">
                                                            <path d="M 0, 0 
               m -4, 0 
               a 4,4 0 1,0 8,0 
               a 4,4 0 1,0 -8,0" fill="var(--bs-primary)" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                                cx="0" cy="0" shape="circle"
                                                                class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                style="transform: translate(50%, 50%);"></path>
                                                        </svg></span><span class="apexcharts-legend-text" rel="1" i="0"
                                                        data:default-text="2024" data:collapsed="false"
                                                        style="color: var(--bs-body-color); font-size: 13px; font-weight: 400; font-family: var(--bs-font-family-base);">2024</span>
                                                </div>
                                                <div class="apexcharts-legend-series" rel="2" seriesname="2023"
                                                    data:collapsed="false" style="margin: 4px 10px;"><span
                                                        class="apexcharts-legend-marker" rel="2" data:collapsed="false"
                                                        style="height: 8px; width: 8px; left: 0px; top: 0px;"><svg
                                                            xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="100%"
                                                            height="100%">
                                                            <path d="M 0, 0 
               m -4, 0 
               a 4,4 0 1,0 8,0 
               a 4,4 0 1,0 -8,0" fill="var(--bs-info)" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                                cx="0" cy="0" shape="circle"
                                                                class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                style="transform: translate(50%, 50%);"></path>
                                                        </svg></span><span class="apexcharts-legend-text" rel="2" i="1"
                                                        data:default-text="2023" data:collapsed="false"
                                                        style="color: var(--bs-body-color); font-size: 13px; font-weight: 400; font-family: var(--bs-font-family-base);">2023</span>
                                                </div>
                                            </div>
                                            <style type="text/css">
                                                .apexcharts-flip-y {
                                                    transform: scaleY(-1) translateY(-100%);
                                                    transform-origin: top;
                                                    transform-box: fill-box;
                                                }

                                                .apexcharts-flip-x {
                                                    transform: scaleX(-1);
                                                    transform-origin: center;
                                                    transform-box: fill-box;
                                                }

                                                .apexcharts-legend {
                                                    display: flex;
                                                    overflow: auto;
                                                    padding: 0 10px;
                                                }

                                                .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                    flex-direction: column;
                                                }

                                                .apexcharts-legend-group {
                                                    display: flex;
                                                }

                                                .apexcharts-legend-group-vertical {
                                                    flex-direction: column-reverse;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom,
                                                .apexcharts-legend.apx-legend-position-top {
                                                    flex-wrap: wrap
                                                }

                                                .apexcharts-legend.apx-legend-position-right,
                                                .apexcharts-legend.apx-legend-position-left {
                                                    flex-direction: column;
                                                    bottom: 0;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                .apexcharts-legend.apx-legend-position-right,
                                                .apexcharts-legend.apx-legend-position-left {
                                                    justify-content: flex-start;
                                                    align-items: flex-start;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                    justify-content: center;
                                                    align-items: center;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                    justify-content: flex-end;
                                                    align-items: flex-end;
                                                }

                                                .apexcharts-legend-series {
                                                    cursor: pointer;
                                                    line-height: normal;
                                                    display: flex;
                                                    align-items: center;
                                                }

                                                .apexcharts-legend-text {
                                                    position: relative;
                                                    font-size: 14px;
                                                }

                                                .apexcharts-legend-text *,
                                                .apexcharts-legend-marker * {
                                                    pointer-events: none;
                                                }

                                                .apexcharts-legend-marker {
                                                    position: relative;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    cursor: pointer;
                                                    margin-right: 1px;
                                                }

                                                .apexcharts-legend-series.apexcharts-no-click {
                                                    cursor: auto;
                                                }

                                                .apexcharts-legend .apexcharts-hidden-zero-series,
                                                .apexcharts-legend .apexcharts-hidden-null-series {
                                                    display: none !important;
                                                }

                                                .apexcharts-inactive-legend {
                                                    opacity: 0.45;
                                                }
                                            </style>
                                        </foreignObject>
                                        <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                        <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                        <g class="apexcharts-yaxis" rel="0" transform="translate(14.215373992919922, 0)">
                                            <g class="apexcharts-yaxis-texts-g"><text x="20" y="57.333333333333336"
                                                    text-anchor="end" dominant-baseline="auto" font-size="13px"
                                                    font-family="var(--bs-font-family-base)" font-weight="400"
                                                    fill="var(--bs-secondary-color)"
                                                    class="apexcharts-text apexcharts-yaxis-label "
                                                    style="font-family: var(--bs-font-family-base);">
                                                    <tspan>30</tspan>
                                                    <title>30</title>
                                                </text><text x="20" y="100.58719989713033" text-anchor="end"
                                                    dominant-baseline="auto" font-size="13px"
                                                    font-family="var(--bs-font-family-base)" font-weight="400"
                                                    fill="var(--bs-secondary-color)"
                                                    class="apexcharts-text apexcharts-yaxis-label "
                                                    style="font-family: var(--bs-font-family-base);">
                                                    <tspan>20</tspan>
                                                    <title>20</title>
                                                </text><text x="20" y="143.84106646092732" text-anchor="end"
                                                    dominant-baseline="auto" font-size="13px"
                                                    font-family="var(--bs-font-family-base)" font-weight="400"
                                                    fill="var(--bs-secondary-color)"
                                                    class="apexcharts-text apexcharts-yaxis-label "
                                                    style="font-family: var(--bs-font-family-base);">
                                                    <tspan>10</tspan>
                                                    <title>10</title>
                                                </text><text x="20" y="187.09493302472433" text-anchor="end"
                                                    dominant-baseline="auto" font-size="13px"
                                                    font-family="var(--bs-font-family-base)" font-weight="400"
                                                    fill="var(--bs-secondary-color)"
                                                    class="apexcharts-text apexcharts-yaxis-label "
                                                    style="font-family: var(--bs-font-family-base);">
                                                    <tspan>0</tspan>
                                                    <title>0</title>
                                                </text><text x="20" y="230.34879958852133" text-anchor="end"
                                                    dominant-baseline="auto" font-size="13px"
                                                    font-family="var(--bs-font-family-base)" font-weight="400"
                                                    fill="var(--bs-secondary-color)"
                                                    class="apexcharts-text apexcharts-yaxis-label "
                                                    style="font-family: var(--bs-font-family-base);">
                                                    <tspan>-10</tspan>
                                                    <title>-10</title>
                                                </text><text x="20" y="273.6026661523183" text-anchor="end"
                                                    dominant-baseline="auto" font-size="13px"
                                                    font-family="var(--bs-font-family-base)" font-weight="400"
                                                    fill="var(--bs-secondary-color)"
                                                    class="apexcharts-text apexcharts-yaxis-label "
                                                    style="font-family: var(--bs-font-family-base);">
                                                    <tspan>-20</tspan>
                                                    <title>-20</title>
                                                </text></g>
                                        </g>
                                        <g class="apexcharts-inner apexcharts-graphical"
                                            transform="translate(52.21537399291992, 53)">
                                            <defs>
                                                <linearGradient x1="0" y1="0" x2="0" y2="1" id="SvgjsLinearGradient1054">
                                                    <stop stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0">
                                                    </stop>
                                                    <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1">
                                                    </stop>
                                                    <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1">
                                                    </stop>
                                                </linearGradient>
                                                <clipPath id="gridRectMaskafaje2rv">
                                                    <rect width="538.7846260070801" height="216.269332818985" x="0" y="0"
                                                        rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                                        stroke-dasharray="0" fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="gridRectBarMaskafaje2rv">
                                                    <rect width="548.7846260070801" height="226.269332818985" x="-5" y="-5"
                                                        rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                                        stroke-dasharray="0" fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="gridRectMarkerMaskafaje2rv">
                                                    <rect width="538.7846260070801" height="216.269332818985" x="0" y="0"
                                                        rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                                        stroke-dasharray="0" fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="forecastMaskafaje2rv"></clipPath>
                                                <clipPath id="nonForecastMaskafaje2rv"></clipPath>
                                            </defs>
                                            <rect width="34.63615452902658" height="216.269332818985" x="0" y="0" rx="0"
                                                ry="0" opacity="1" stroke-width="0" stroke="#b6b6b6" stroke-dasharray="3"
                                                fill="url(#SvgjsLinearGradient1054)" class="apexcharts-xcrosshairs"
                                                y2="216.269332818985" filter="none" fill-opacity="0.9"></rect>
                                            <g class="apexcharts-grid">
                                                <g class="apexcharts-gridlines-horizontal">
                                                    <line x1="0" y1="43.253866563797" x2="538.7846260070801"
                                                        y2="43.253866563797" stroke="var(--bs-border-color)"
                                                        stroke-dasharray="7" stroke-linecap="butt"
                                                        class="apexcharts-gridline"></line>
                                                    <line x1="0" y1="86.507733127594" x2="538.7846260070801"
                                                        y2="86.507733127594" stroke="var(--bs-border-color)"
                                                        stroke-dasharray="7" stroke-linecap="butt"
                                                        class="apexcharts-gridline"></line>
                                                    <line x1="0" y1="129.76159969139098" x2="538.7846260070801"
                                                        y2="129.76159969139098" stroke="var(--bs-border-color)"
                                                        stroke-dasharray="7" stroke-linecap="butt"
                                                        class="apexcharts-gridline"></line>
                                                    <line x1="0" y1="173.015466255188" x2="538.7846260070801"
                                                        y2="173.015466255188" stroke="var(--bs-border-color)"
                                                        stroke-dasharray="7" stroke-linecap="butt"
                                                        class="apexcharts-gridline"></line>
                                                </g>
                                                <g class="apexcharts-gridlines-vertical"></g>
                                                <line x1="0" y1="216.269332818985" x2="538.7846260070801"
                                                    y2="216.269332818985" stroke="transparent" stroke-dasharray="0"
                                                    stroke-linecap="butt"></line>
                                                <line x1="0" y1="1" x2="0" y2="216.269332818985" stroke="transparent"
                                                    stroke-dasharray="0" stroke-linecap="butt"></line>
                                            </g>
                                            <g class="apexcharts-grid-borders">
                                                <line x1="0" y1="0" x2="538.7846260070801" y2="0"
                                                    stroke="var(--bs-border-color)" stroke-dasharray="7"
                                                    stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                <line x1="0" y1="216.269332818985" x2="538.7846260070801"
                                                    y2="216.269332818985" stroke="var(--bs-border-color)"
                                                    stroke-dasharray="7" stroke-linecap="butt" class="apexcharts-gridline">
                                                </line>
                                            </g>
                                            <g class="apexcharts-bar-series apexcharts-plot-series">
                                                <g class="apexcharts-series" seriesName="2024" rel="1" data:realIndex="0">
                                                    <path
                                                        d="M 24.166538878849575 115.76259969139099 L 24.166538878849575 65.90563987655639 C 24.166538878849575 60.40563987655639 29.66653887884958 54.905639876556386 35.16653887884958 54.905639876556386 L 41.80269340787615 54.905639876556386 C 47.30269340787615 54.905639876556386 52.80269340787615 60.40563987655639 52.80269340787615 65.90563987655639 L 52.80269340787615 115.76259969139099 C 52.80269340787615 121.26259969139099 47.30269340787615 126.76259969139099 41.80269340787615 126.76259969139099 L 35.16653887884958 126.76259969139099 C 29.66653887884958 126.76259969139099 24.166538878849575 121.26259969139099 24.166538878849575 115.76259969139099 Z "
                                                        fill="var(--bs-primary)" fill-opacity="1"
                                                        stroke="var(--bs-paper-bg)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="6" stroke-dasharray="0"
                                                        class="apexcharts-bar-area " index="0"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 24.166538878849575 115.76259969139099 L 24.166538878849575 65.90563987655639 C 24.166538878849575 60.40563987655639 29.66653887884958 54.905639876556386 35.16653887884958 54.905639876556386 L 41.80269340787615 54.905639876556386 C 47.30269340787615 54.905639876556386 52.80269340787615 60.40563987655639 52.80269340787615 65.90563987655639 L 52.80269340787615 115.76259969139099 C 52.80269340787615 121.26259969139099 47.30269340787615 126.76259969139099 41.80269340787615 126.76259969139099 L 35.16653887884958 126.76259969139099 C 29.66653887884958 126.76259969139099 24.166538878849575 121.26259969139099 24.166538878849575 115.76259969139099 Z "
                                                        pathFrom="M 24.166538878849575 126.76259969139099 L 24.166538878849575 126.76259969139099 L 52.80269340787615 126.76259969139099 L 52.80269340787615 126.76259969139099 L 52.80269340787615 126.76259969139099 L 52.80269340787615 126.76259969139099 L 52.80269340787615 126.76259969139099 L 24.166538878849575 126.76259969139099 Z"
                                                        cy="51.90463987655639" cx="95.1357711655753" j="0" val="18"
                                                        barHeight="77.8569598148346" barWidth="34.63615452902658"></path>
                                                    <path
                                                        d="M 101.1357711655753 115.76259969139099 L 101.1357711655753 113.48489309673309 C 101.1357711655753 107.98489309673309 106.6357711655753 102.48489309673309 112.1357711655753 102.48489309673309 L 118.77192569460189 102.48489309673309 C 124.27192569460189 102.48489309673309 129.7719256946019 107.98489309673309 129.7719256946019 113.48489309673309 L 129.7719256946019 115.76259969139099 C 129.7719256946019 121.26259969139099 124.27192569460189 126.76259969139099 118.77192569460189 126.76259969139099 L 112.1357711655753 126.76259969139099 C 106.6357711655753 126.76259969139099 101.1357711655753 121.26259969139099 101.1357711655753 115.76259969139099 Z "
                                                        fill="var(--bs-primary)" fill-opacity="1"
                                                        stroke="var(--bs-paper-bg)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="6" stroke-dasharray="0"
                                                        class="apexcharts-bar-area " index="0"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 101.1357711655753 115.76259969139099 L 101.1357711655753 113.48489309673309 C 101.1357711655753 107.98489309673309 106.6357711655753 102.48489309673309 112.1357711655753 102.48489309673309 L 118.77192569460189 102.48489309673309 C 124.27192569460189 102.48489309673309 129.7719256946019 107.98489309673309 129.7719256946019 113.48489309673309 L 129.7719256946019 115.76259969139099 C 129.7719256946019 121.26259969139099 124.27192569460189 126.76259969139099 118.77192569460189 126.76259969139099 L 112.1357711655753 126.76259969139099 C 106.6357711655753 126.76259969139099 101.1357711655753 121.26259969139099 101.1357711655753 115.76259969139099 Z "
                                                        pathFrom="M 101.1357711655753 126.76259969139099 L 101.1357711655753 126.76259969139099 L 129.7719256946019 126.76259969139099 L 129.7719256946019 126.76259969139099 L 129.7719256946019 126.76259969139099 L 129.7719256946019 126.76259969139099 L 129.7719256946019 126.76259969139099 L 101.1357711655753 126.76259969139099 Z"
                                                        cy="99.48389309673308" cx="172.10500345230105" j="1" val="7"
                                                        barHeight="30.2777065946579" barWidth="34.63615452902658"></path>
                                                    <path
                                                        d="M 178.10500345230105 115.76259969139099 L 178.10500345230105 78.88179984569548 C 178.10500345230105 73.38179984569548 183.60500345230105 67.88179984569548 189.10500345230105 67.88179984569548 L 195.74115798132763 67.88179984569548 C 201.24115798132763 67.88179984569548 206.74115798132763 73.38179984569548 206.74115798132763 78.88179984569548 L 206.74115798132763 115.76259969139099 C 206.74115798132763 121.26259969139099 201.24115798132763 126.76259969139099 195.74115798132763 126.76259969139099 L 189.10500345230105 126.76259969139099 C 183.60500345230105 126.76259969139099 178.10500345230105 121.26259969139099 178.10500345230105 115.76259969139099 Z "
                                                        fill="var(--bs-primary)" fill-opacity="1"
                                                        stroke="var(--bs-paper-bg)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="6" stroke-dasharray="0"
                                                        class="apexcharts-bar-area " index="0"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 178.10500345230105 115.76259969139099 L 178.10500345230105 78.88179984569548 C 178.10500345230105 73.38179984569548 183.60500345230105 67.88179984569548 189.10500345230105 67.88179984569548 L 195.74115798132763 67.88179984569548 C 201.24115798132763 67.88179984569548 206.74115798132763 73.38179984569548 206.74115798132763 78.88179984569548 L 206.74115798132763 115.76259969139099 C 206.74115798132763 121.26259969139099 201.24115798132763 126.76259969139099 195.74115798132763 126.76259969139099 L 189.10500345230105 126.76259969139099 C 183.60500345230105 126.76259969139099 178.10500345230105 121.26259969139099 178.10500345230105 115.76259969139099 Z "
                                                        pathFrom="M 178.10500345230105 126.76259969139099 L 178.10500345230105 126.76259969139099 L 206.74115798132763 126.76259969139099 L 206.74115798132763 126.76259969139099 L 206.74115798132763 126.76259969139099 L 206.74115798132763 126.76259969139099 L 206.74115798132763 126.76259969139099 L 178.10500345230105 126.76259969139099 Z"
                                                        cy="64.88079984569548" cx="249.0742357390268" j="2" val="15"
                                                        barHeight="64.8807998456955" barWidth="34.63615452902658"></path>
                                                    <path
                                                        d="M 255.0742357390268 115.76259969139099 L 255.0742357390268 18.326386656379675 C 255.0742357390268 12.826386656379675 260.5742357390268 7.326386656379677 266.0742357390268 7.326386656379677 L 272.71039026805335 7.326386656379677 C 278.21039026805335 7.326386656379677 283.71039026805335 12.826386656379675 283.71039026805335 18.326386656379675 L 283.71039026805335 115.76259969139099 C 283.71039026805335 121.26259969139099 278.21039026805335 126.76259969139099 272.71039026805335 126.76259969139099 L 266.0742357390268 126.76259969139099 C 260.5742357390268 126.76259969139099 255.0742357390268 121.26259969139099 255.0742357390268 115.76259969139099 Z "
                                                        fill="var(--bs-primary)" fill-opacity="1"
                                                        stroke="var(--bs-paper-bg)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="6" stroke-dasharray="0"
                                                        class="apexcharts-bar-area " index="0"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 255.0742357390268 115.76259969139099 L 255.0742357390268 18.326386656379675 C 255.0742357390268 12.826386656379675 260.5742357390268 7.326386656379677 266.0742357390268 7.326386656379677 L 272.71039026805335 7.326386656379677 C 278.21039026805335 7.326386656379677 283.71039026805335 12.826386656379675 283.71039026805335 18.326386656379675 L 283.71039026805335 115.76259969139099 C 283.71039026805335 121.26259969139099 278.21039026805335 126.76259969139099 272.71039026805335 126.76259969139099 L 266.0742357390268 126.76259969139099 C 260.5742357390268 126.76259969139099 255.0742357390268 121.26259969139099 255.0742357390268 115.76259969139099 Z "
                                                        pathFrom="M 255.0742357390268 126.76259969139099 L 255.0742357390268 126.76259969139099 L 283.71039026805335 126.76259969139099 L 283.71039026805335 126.76259969139099 L 283.71039026805335 126.76259969139099 L 283.71039026805335 126.76259969139099 L 283.71039026805335 126.76259969139099 L 255.0742357390268 126.76259969139099 Z"
                                                        cy="4.325386656379678" cx="326.04346802575253" j="3" val="29"
                                                        barHeight="125.4362130350113" barWidth="34.63615452902658"></path>
                                                    <path
                                                        d="M 332.04346802575253 115.76259969139099 L 332.04346802575253 65.90563987655639 C 332.04346802575253 60.40563987655639 337.54346802575253 54.905639876556386 343.04346802575253 54.905639876556386 L 349.6796225547791 54.905639876556386 C 355.1796225547791 54.905639876556386 360.6796225547791 60.40563987655639 360.6796225547791 65.90563987655639 L 360.6796225547791 115.76259969139099 C 360.6796225547791 121.26259969139099 355.1796225547791 126.76259969139099 349.6796225547791 126.76259969139099 L 343.04346802575253 126.76259969139099 C 337.54346802575253 126.76259969139099 332.04346802575253 121.26259969139099 332.04346802575253 115.76259969139099 Z "
                                                        fill="var(--bs-primary)" fill-opacity="1"
                                                        stroke="var(--bs-paper-bg)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="6" stroke-dasharray="0"
                                                        class="apexcharts-bar-area " index="0"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 332.04346802575253 115.76259969139099 L 332.04346802575253 65.90563987655639 C 332.04346802575253 60.40563987655639 337.54346802575253 54.905639876556386 343.04346802575253 54.905639876556386 L 349.6796225547791 54.905639876556386 C 355.1796225547791 54.905639876556386 360.6796225547791 60.40563987655639 360.6796225547791 65.90563987655639 L 360.6796225547791 115.76259969139099 C 360.6796225547791 121.26259969139099 355.1796225547791 126.76259969139099 349.6796225547791 126.76259969139099 L 343.04346802575253 126.76259969139099 C 337.54346802575253 126.76259969139099 332.04346802575253 121.26259969139099 332.04346802575253 115.76259969139099 Z "
                                                        pathFrom="M 332.04346802575253 126.76259969139099 L 332.04346802575253 126.76259969139099 L 360.6796225547791 126.76259969139099 L 360.6796225547791 126.76259969139099 L 360.6796225547791 126.76259969139099 L 360.6796225547791 126.76259969139099 L 360.6796225547791 126.76259969139099 L 332.04346802575253 126.76259969139099 Z"
                                                        cy="51.90463987655639" cx="403.0127003124783" j="4" val="18"
                                                        barHeight="77.8569598148346" barWidth="34.63615452902658"></path>
                                                    <path
                                                        d="M 409.0127003124783 115.76259969139099 L 409.0127003124783 91.85795981483459 C 409.0127003124783 86.35795981483459 414.5127003124783 80.85795981483459 420.0127003124783 80.85795981483459 L 426.64885484150483 80.85795981483459 C 432.14885484150483 80.85795981483459 437.64885484150483 86.35795981483459 437.64885484150483 91.85795981483459 L 437.64885484150483 115.76259969139099 C 437.64885484150483 121.26259969139099 432.14885484150483 126.76259969139099 426.64885484150483 126.76259969139099 L 420.0127003124783 126.76259969139099 C 414.5127003124783 126.76259969139099 409.0127003124783 121.26259969139099 409.0127003124783 115.76259969139099 Z "
                                                        fill="var(--bs-primary)" fill-opacity="1"
                                                        stroke="var(--bs-paper-bg)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="6" stroke-dasharray="0"
                                                        class="apexcharts-bar-area " index="0"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 409.0127003124783 115.76259969139099 L 409.0127003124783 91.85795981483459 C 409.0127003124783 86.35795981483459 414.5127003124783 80.85795981483459 420.0127003124783 80.85795981483459 L 426.64885484150483 80.85795981483459 C 432.14885484150483 80.85795981483459 437.64885484150483 86.35795981483459 437.64885484150483 91.85795981483459 L 437.64885484150483 115.76259969139099 C 437.64885484150483 121.26259969139099 432.14885484150483 126.76259969139099 426.64885484150483 126.76259969139099 L 420.0127003124783 126.76259969139099 C 414.5127003124783 126.76259969139099 409.0127003124783 121.26259969139099 409.0127003124783 115.76259969139099 Z "
                                                        pathFrom="M 409.0127003124783 126.76259969139099 L 409.0127003124783 126.76259969139099 L 437.64885484150483 126.76259969139099 L 437.64885484150483 126.76259969139099 L 437.64885484150483 126.76259969139099 L 437.64885484150483 126.76259969139099 L 437.64885484150483 126.76259969139099 L 409.0127003124783 126.76259969139099 Z"
                                                        cy="77.85695981483458" cx="479.981932599204" j="5" val="12"
                                                        barHeight="51.9046398765564" barWidth="34.63615452902658"></path>
                                                    <path
                                                        d="M 485.981932599204 115.76259969139099 L 485.981932599204 104.83411978397369 C 485.981932599204 99.33411978397369 491.481932599204 93.83411978397369 496.981932599204 93.83411978397369 L 503.6180871282306 93.83411978397369 C 509.1180871282306 93.83411978397369 514.6180871282306 99.33411978397369 514.6180871282306 104.83411978397369 L 514.6180871282306 115.76259969139099 C 514.6180871282306 121.26259969139099 509.1180871282306 126.76259969139099 503.6180871282306 126.76259969139099 L 496.981932599204 126.76259969139099 C 491.481932599204 126.76259969139099 485.981932599204 121.26259969139099 485.981932599204 115.76259969139099 Z "
                                                        fill="var(--bs-primary)" fill-opacity="1"
                                                        stroke="var(--bs-paper-bg)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="6" stroke-dasharray="0"
                                                        class="apexcharts-bar-area " index="0"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 485.981932599204 115.76259969139099 L 485.981932599204 104.83411978397369 C 485.981932599204 99.33411978397369 491.481932599204 93.83411978397369 496.981932599204 93.83411978397369 L 503.6180871282306 93.83411978397369 C 509.1180871282306 93.83411978397369 514.6180871282306 99.33411978397369 514.6180871282306 104.83411978397369 L 514.6180871282306 115.76259969139099 C 514.6180871282306 121.26259969139099 509.1180871282306 126.76259969139099 503.6180871282306 126.76259969139099 L 496.981932599204 126.76259969139099 C 491.481932599204 126.76259969139099 485.981932599204 121.26259969139099 485.981932599204 115.76259969139099 Z "
                                                        pathFrom="M 485.981932599204 126.76259969139099 L 485.981932599204 126.76259969139099 L 514.6180871282306 126.76259969139099 L 514.6180871282306 126.76259969139099 L 514.6180871282306 126.76259969139099 L 514.6180871282306 126.76259969139099 L 514.6180871282306 126.76259969139099 L 485.981932599204 126.76259969139099 Z"
                                                        cy="90.83311978397369" cx="556.9511648859298" j="6" val="9"
                                                        barHeight="38.9284799074173" barWidth="34.63615452902658"></path>
                                                    <g class="apexcharts-bar-goals-markers">
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                    </g>
                                                </g>
                                                <g class="apexcharts-series" seriesName="2023" rel="2" data:realIndex="1">
                                                    <path
                                                        d="M 24.166538878849575 143.763599691391 L 24.166538878849575 171.9936262243271 C 24.166538878849575 177.4936262243271 29.66653887884958 182.9936262243271 35.16653887884958 182.9936262243271 L 41.80269340787615 182.9936262243271 C 47.30269340787615 182.9936262243271 52.80269340787615 177.4936262243271 52.80269340787615 171.9936262243271 L 52.80269340787615 143.763599691391 C 52.80269340787615 138.263599691391 47.30269340787615 132.763599691391 41.80269340787615 132.763599691391 L 35.16653887884958 132.763599691391 C 29.66653887884958 132.763599691391 24.166538878849575 138.263599691391 24.166538878849575 143.763599691391 Z "
                                                        fill="var(--bs-info)" fill-opacity="1" stroke="var(--bs-paper-bg)"
                                                        stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                        stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 24.166538878849575 143.763599691391 L 24.166538878849575 171.9936262243271 C 24.166538878849575 177.4936262243271 29.66653887884958 182.9936262243271 35.16653887884958 182.9936262243271 L 41.80269340787615 182.9936262243271 C 47.30269340787615 182.9936262243271 52.80269340787615 177.4936262243271 52.80269340787615 171.9936262243271 L 52.80269340787615 143.763599691391 C 52.80269340787615 138.263599691391 47.30269340787615 132.763599691391 41.80269340787615 132.763599691391 L 35.16653887884958 132.763599691391 C 29.66653887884958 132.763599691391 24.166538878849575 138.263599691391 24.166538878849575 143.763599691391 Z "
                                                        pathFrom="M 24.166538878849575 132.763599691391 L 24.166538878849575 132.763599691391 L 52.80269340787615 132.763599691391 L 52.80269340787615 132.763599691391 L 52.80269340787615 132.763599691391 L 52.80269340787615 132.763599691391 L 52.80269340787615 132.763599691391 L 24.166538878849575 132.763599691391 Z"
                                                        cy="185.9926262243271" cx="95.1357711655753" j="0" val="-13"
                                                        barHeight="-56.2300265329361" barWidth="34.63615452902658"></path>
                                                    <path
                                                        d="M 101.1357711655753 143.763599691391 L 101.1357711655753 193.6205595062256 C 101.1357711655753 199.1205595062256 106.6357711655753 204.6205595062256 112.1357711655753 204.6205595062256 L 118.77192569460189 204.6205595062256 C 124.27192569460189 204.6205595062256 129.7719256946019 199.1205595062256 129.7719256946019 193.6205595062256 L 129.7719256946019 143.763599691391 C 129.7719256946019 138.263599691391 124.27192569460189 132.763599691391 118.77192569460189 132.763599691391 L 112.1357711655753 132.763599691391 C 106.6357711655753 132.763599691391 101.1357711655753 138.263599691391 101.1357711655753 143.763599691391 Z "
                                                        fill="var(--bs-info)" fill-opacity="1" stroke="var(--bs-paper-bg)"
                                                        stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                        stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 101.1357711655753 143.763599691391 L 101.1357711655753 193.6205595062256 C 101.1357711655753 199.1205595062256 106.6357711655753 204.6205595062256 112.1357711655753 204.6205595062256 L 118.77192569460189 204.6205595062256 C 124.27192569460189 204.6205595062256 129.7719256946019 199.1205595062256 129.7719256946019 193.6205595062256 L 129.7719256946019 143.763599691391 C 129.7719256946019 138.263599691391 124.27192569460189 132.763599691391 118.77192569460189 132.763599691391 L 112.1357711655753 132.763599691391 C 106.6357711655753 132.763599691391 101.1357711655753 138.263599691391 101.1357711655753 143.763599691391 Z "
                                                        pathFrom="M 101.1357711655753 132.763599691391 L 101.1357711655753 132.763599691391 L 129.7719256946019 132.763599691391 L 129.7719256946019 132.763599691391 L 129.7719256946019 132.763599691391 L 129.7719256946019 132.763599691391 L 129.7719256946019 132.763599691391 L 101.1357711655753 132.763599691391 Z"
                                                        cy="207.61955950622558" cx="172.10500345230105" j="1" val="-18"
                                                        barHeight="-77.8569598148346" barWidth="34.63615452902658"></path>
                                                    <path
                                                        d="M 178.10500345230105 143.763599691391 L 178.10500345230105 154.6920795988083 C 178.10500345230105 160.1920795988083 183.60500345230105 165.6920795988083 189.10500345230105 165.6920795988083 L 195.74115798132763 165.6920795988083 C 201.24115798132763 165.6920795988083 206.74115798132763 160.1920795988083 206.74115798132763 154.6920795988083 L 206.74115798132763 143.763599691391 C 206.74115798132763 138.263599691391 201.24115798132763 132.763599691391 195.74115798132763 132.763599691391 L 189.10500345230105 132.763599691391 C 183.60500345230105 132.763599691391 178.10500345230105 138.263599691391 178.10500345230105 143.763599691391 Z "
                                                        fill="var(--bs-info)" fill-opacity="1" stroke="var(--bs-paper-bg)"
                                                        stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                        stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 178.10500345230105 143.763599691391 L 178.10500345230105 154.6920795988083 C 178.10500345230105 160.1920795988083 183.60500345230105 165.6920795988083 189.10500345230105 165.6920795988083 L 195.74115798132763 165.6920795988083 C 201.24115798132763 165.6920795988083 206.74115798132763 160.1920795988083 206.74115798132763 154.6920795988083 L 206.74115798132763 143.763599691391 C 206.74115798132763 138.263599691391 201.24115798132763 132.763599691391 195.74115798132763 132.763599691391 L 189.10500345230105 132.763599691391 C 183.60500345230105 132.763599691391 178.10500345230105 138.263599691391 178.10500345230105 143.763599691391 Z "
                                                        pathFrom="M 178.10500345230105 132.763599691391 L 178.10500345230105 132.763599691391 L 206.74115798132763 132.763599691391 L 206.74115798132763 132.763599691391 L 206.74115798132763 132.763599691391 L 206.74115798132763 132.763599691391 L 206.74115798132763 132.763599691391 L 178.10500345230105 132.763599691391 Z"
                                                        cy="168.6910795988083" cx="249.0742357390268" j="2" val="-9"
                                                        barHeight="-38.9284799074173" barWidth="34.63615452902658"></path>
                                                    <path
                                                        d="M 255.0742357390268 143.763599691391 L 255.0742357390268 176.3190128807068 C 255.0742357390268 181.8190128807068 260.5742357390268 187.3190128807068 266.0742357390268 187.3190128807068 L 272.71039026805335 187.3190128807068 C 278.21039026805335 187.3190128807068 283.71039026805335 181.8190128807068 283.71039026805335 176.3190128807068 L 283.71039026805335 143.763599691391 C 283.71039026805335 138.263599691391 278.21039026805335 132.763599691391 272.71039026805335 132.763599691391 L 266.0742357390268 132.763599691391 C 260.5742357390268 132.763599691391 255.0742357390268 138.263599691391 255.0742357390268 143.763599691391 Z "
                                                        fill="var(--bs-info)" fill-opacity="1" stroke="var(--bs-paper-bg)"
                                                        stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                        stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 255.0742357390268 143.763599691391 L 255.0742357390268 176.3190128807068 C 255.0742357390268 181.8190128807068 260.5742357390268 187.3190128807068 266.0742357390268 187.3190128807068 L 272.71039026805335 187.3190128807068 C 278.21039026805335 187.3190128807068 283.71039026805335 181.8190128807068 283.71039026805335 176.3190128807068 L 283.71039026805335 143.763599691391 C 283.71039026805335 138.263599691391 278.21039026805335 132.763599691391 272.71039026805335 132.763599691391 L 266.0742357390268 132.763599691391 C 260.5742357390268 132.763599691391 255.0742357390268 138.263599691391 255.0742357390268 143.763599691391 Z "
                                                        pathFrom="M 255.0742357390268 132.763599691391 L 255.0742357390268 132.763599691391 L 283.71039026805335 132.763599691391 L 283.71039026805335 132.763599691391 L 283.71039026805335 132.763599691391 L 283.71039026805335 132.763599691391 L 283.71039026805335 132.763599691391 L 255.0742357390268 132.763599691391 Z"
                                                        cy="190.3180128807068" cx="326.04346802575253" j="3" val="-14"
                                                        barHeight="-60.5554131893158" barWidth="34.63615452902658"></path>
                                                    <path
                                                        d="M 332.04346802575253 143.763599691391 L 332.04346802575253 150.36669294242859 C 332.04346802575253 155.86669294242859 337.54346802575253 161.36669294242859 343.04346802575253 161.36669294242859 L 349.6796225547791 161.36669294242859 C 355.1796225547791 161.36669294242859 360.6796225547791 155.86669294242859 360.6796225547791 150.36669294242859 L 360.6796225547791 143.763599691391 C 360.6796225547791 138.263599691391 355.1796225547791 132.763599691391 349.6796225547791 132.763599691391 L 343.04346802575253 132.763599691391 C 337.54346802575253 132.763599691391 332.04346802575253 138.263599691391 332.04346802575253 143.763599691391 Z "
                                                        fill="var(--bs-info)" fill-opacity="1" stroke="var(--bs-paper-bg)"
                                                        stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                        stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 332.04346802575253 143.763599691391 L 332.04346802575253 150.36669294242859 C 332.04346802575253 155.86669294242859 337.54346802575253 161.36669294242859 343.04346802575253 161.36669294242859 L 349.6796225547791 161.36669294242859 C 355.1796225547791 161.36669294242859 360.6796225547791 155.86669294242859 360.6796225547791 150.36669294242859 L 360.6796225547791 143.763599691391 C 360.6796225547791 138.263599691391 355.1796225547791 132.763599691391 349.6796225547791 132.763599691391 L 343.04346802575253 132.763599691391 C 337.54346802575253 132.763599691391 332.04346802575253 138.263599691391 332.04346802575253 143.763599691391 Z "
                                                        pathFrom="M 332.04346802575253 132.763599691391 L 332.04346802575253 132.763599691391 L 360.6796225547791 132.763599691391 L 360.6796225547791 132.763599691391 L 360.6796225547791 132.763599691391 L 360.6796225547791 132.763599691391 L 360.6796225547791 132.763599691391 L 332.04346802575253 132.763599691391 Z"
                                                        cy="164.36569294242858" cx="403.0127003124783" j="4" val="-8"
                                                        barHeight="-34.6030932510376" barWidth="34.63615452902658"></path>
                                                    <path
                                                        d="M 409.0127003124783 143.763599691391 L 409.0127003124783 189.2951728498459 C 409.0127003124783 194.7951728498459 414.5127003124783 200.2951728498459 420.0127003124783 200.2951728498459 L 426.64885484150483 200.2951728498459 C 432.14885484150483 200.2951728498459 437.64885484150483 194.7951728498459 437.64885484150483 189.2951728498459 L 437.64885484150483 143.763599691391 C 437.64885484150483 138.263599691391 432.14885484150483 132.763599691391 426.64885484150483 132.763599691391 L 420.0127003124783 132.763599691391 C 414.5127003124783 132.763599691391 409.0127003124783 138.263599691391 409.0127003124783 143.763599691391 Z "
                                                        fill="var(--bs-info)" fill-opacity="1" stroke="var(--bs-paper-bg)"
                                                        stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                        stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 409.0127003124783 143.763599691391 L 409.0127003124783 189.2951728498459 C 409.0127003124783 194.7951728498459 414.5127003124783 200.2951728498459 420.0127003124783 200.2951728498459 L 426.64885484150483 200.2951728498459 C 432.14885484150483 200.2951728498459 437.64885484150483 194.7951728498459 437.64885484150483 189.2951728498459 L 437.64885484150483 143.763599691391 C 437.64885484150483 138.263599691391 432.14885484150483 132.763599691391 426.64885484150483 132.763599691391 L 420.0127003124783 132.763599691391 C 414.5127003124783 132.763599691391 409.0127003124783 138.263599691391 409.0127003124783 143.763599691391 Z "
                                                        pathFrom="M 409.0127003124783 132.763599691391 L 409.0127003124783 132.763599691391 L 437.64885484150483 132.763599691391 L 437.64885484150483 132.763599691391 L 437.64885484150483 132.763599691391 L 437.64885484150483 132.763599691391 L 437.64885484150483 132.763599691391 L 409.0127003124783 132.763599691391 Z"
                                                        cy="203.2941728498459" cx="479.981932599204" j="5" val="-17"
                                                        barHeight="-73.5315731584549" barWidth="34.63615452902658"></path>
                                                    <path
                                                        d="M 485.981932599204 143.763599691391 L 485.981932599204 180.6443995370865 C 485.981932599204 186.1443995370865 491.481932599204 191.6443995370865 496.981932599204 191.6443995370865 L 503.6180871282306 191.6443995370865 C 509.1180871282306 191.6443995370865 514.6180871282306 186.1443995370865 514.6180871282306 180.6443995370865 L 514.6180871282306 143.763599691391 C 514.6180871282306 138.263599691391 509.1180871282306 132.763599691391 503.6180871282306 132.763599691391 L 496.981932599204 132.763599691391 C 491.481932599204 132.763599691391 485.981932599204 138.263599691391 485.981932599204 143.763599691391 Z "
                                                        fill="var(--bs-info)" fill-opacity="1" stroke="var(--bs-paper-bg)"
                                                        stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                        stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                        clip-path="url(#gridRectBarMaskafaje2rv)"
                                                        pathTo="M 485.981932599204 143.763599691391 L 485.981932599204 180.6443995370865 C 485.981932599204 186.1443995370865 491.481932599204 191.6443995370865 496.981932599204 191.6443995370865 L 503.6180871282306 191.6443995370865 C 509.1180871282306 191.6443995370865 514.6180871282306 186.1443995370865 514.6180871282306 180.6443995370865 L 514.6180871282306 143.763599691391 C 514.6180871282306 138.263599691391 509.1180871282306 132.763599691391 503.6180871282306 132.763599691391 L 496.981932599204 132.763599691391 C 491.481932599204 132.763599691391 485.981932599204 138.263599691391 485.981932599204 143.763599691391 Z "
                                                        pathFrom="M 485.981932599204 132.763599691391 L 485.981932599204 132.763599691391 L 514.6180871282306 132.763599691391 L 514.6180871282306 132.763599691391 L 514.6180871282306 132.763599691391 L 514.6180871282306 132.763599691391 L 514.6180871282306 132.763599691391 L 485.981932599204 132.763599691391 Z"
                                                        cy="194.6433995370865" cx="556.9511648859298" j="6" val="-15"
                                                        barHeight="-64.8807998456955" barWidth="34.63615452902658"></path>
                                                    <g class="apexcharts-bar-goals-markers">
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskafaje2rv)"></g>
                                                    </g>
                                                </g>
                                                <g class="apexcharts-datalabels" data:realIndex="0"></g>
                                                <g class="apexcharts-datalabels" data:realIndex="1"></g>
                                            </g>
                                            <line x1="0" y1="0" x2="538.7846260070801" y2="0" stroke="#b6b6b6"
                                                stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                                class="apexcharts-ycrosshairs"></line>
                                            <line x1="0" y1="0" x2="538.7846260070801" y2="0" stroke="#b6b6b6"
                                                stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                                class="apexcharts-ycrosshairs-hidden"></line>
                                            <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                <g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text
                                                        x="38.484616143362864" y="244.269332818985" text-anchor="middle"
                                                        dominant-baseline="auto" font-size="13px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>Jan</tspan>
                                                        <title>Jan</title>
                                                    </text><text x="115.45384843008858" y="244.269332818985"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>Feb</tspan>
                                                        <title>Feb</title>
                                                    </text><text x="192.4230807168143" y="244.269332818985"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>Mar</tspan>
                                                        <title>Mar</title>
                                                    </text><text x="269.39231300354004" y="244.269332818985"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>Apr</tspan>
                                                        <title>Apr</title>
                                                    </text><text x="346.3615452902658" y="244.269332818985"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>May</tspan>
                                                        <title>May</title>
                                                    </text><text x="423.3307775769915" y="244.269332818985"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>Jun</tspan>
                                                        <title>Jun</title>
                                                    </text><text x="500.3000098637172" y="244.269332818985"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>Jul</tspan>
                                                        <title>Jul</title>
                                                    </text></g>
                                            </g>
                                            <g class="apexcharts-yaxis-annotations"></g>
                                            <g class="apexcharts-xaxis-annotations"></g>
                                            <g class="apexcharts-point-annotations"></g>
                                        </g>
                                    </svg>
                                    <div class="apexcharts-tooltip apexcharts-theme-light">
                                        <div class="apexcharts-tooltip-title"
                                            style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                        <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                            style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                style="background-color: var(--bs-primary);"></span>
                                            <div class="apexcharts-tooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                        <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-1"
                                            style="order: 2;"><span class="apexcharts-tooltip-marker"
                                                style="background-color: var(--bs-info);"></span>
                                            <div class="apexcharts-tooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                        <div class="apexcharts-yaxistooltip-text"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card-body px-xl-9 py-12 d-flex align-items-center flex-column">
                                <div class="text-center mb-6">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-label-primary">
                                            <script>
                                                document.write(new Date().getFullYear() - 1);
                                            </script>2024
                                        </button>
                                        <button type="button"
                                            class="btn btn-label-primary dropdown-toggle dropdown-toggle-split"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0);">2021</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">2020</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">2019</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div id="growthChart" style="min-height: 150px;">
                                    <div id="apexcharts5oqu0pq6"
                                        class="apexcharts-canvas apexcharts5oqu0pq6 apexcharts-theme-"
                                        style="width: 300px; height: 150px;"><svg xmlns="http://www.w3.org/2000/svg"
                                            version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg"
                                            xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="300" height="150">
                                            <foreignObject x="0" y="0" width="300" height="150">
                                                <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml"></div>
                                                <style type="text/css">
                                                    .apexcharts-flip-y {
                                                        transform: scaleY(-1) translateY(-100%);
                                                        transform-origin: top;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-flip-x {
                                                        transform: scaleX(-1);
                                                        transform-origin: center;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-legend {
                                                        display: flex;
                                                        overflow: auto;
                                                        padding: 0 10px;
                                                    }

                                                    .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                        flex-direction: column;
                                                    }

                                                    .apexcharts-legend-group {
                                                        display: flex;
                                                    }

                                                    .apexcharts-legend-group-vertical {
                                                        flex-direction: column-reverse;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom,
                                                    .apexcharts-legend.apx-legend-position-top {
                                                        flex-wrap: wrap
                                                    }

                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        flex-direction: column;
                                                        bottom: 0;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        justify-content: flex-start;
                                                        align-items: flex-start;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                        justify-content: center;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                        justify-content: flex-end;
                                                        align-items: flex-end;
                                                    }

                                                    .apexcharts-legend-series {
                                                        cursor: pointer;
                                                        line-height: normal;
                                                        display: flex;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend-text {
                                                        position: relative;
                                                        font-size: 14px;
                                                    }

                                                    .apexcharts-legend-text *,
                                                    .apexcharts-legend-marker * {
                                                        pointer-events: none;
                                                    }

                                                    .apexcharts-legend-marker {
                                                        position: relative;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        cursor: pointer;
                                                        margin-right: 1px;
                                                    }

                                                    .apexcharts-legend-series.apexcharts-no-click {
                                                        cursor: auto;
                                                    }

                                                    .apexcharts-legend .apexcharts-hidden-zero-series,
                                                    .apexcharts-legend .apexcharts-hidden-null-series {
                                                        display: none !important;
                                                    }

                                                    .apexcharts-inactive-legend {
                                                        opacity: 0.45;
                                                    }
                                                </style>
                                            </foreignObject>
                                            <g class="apexcharts-inner apexcharts-graphical" transform="translate(45, -25)">
                                                <defs>
                                                    <clipPath id="gridRectMask5oqu0pq6">
                                                        <rect width="210" height="255" x="0" y="0" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff">
                                                        </rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectBarMask5oqu0pq6">
                                                        <rect width="216" height="261" x="-3" y="-3" rx="0" ry="0"
                                                            opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectMarkerMask5oqu0pq6">
                                                        <rect width="210" height="255" x="0" y="0" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff">
                                                        </rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMask5oqu0pq6"></clipPath>
                                                    <clipPath id="nonForecastMask5oqu0pq6"></clipPath>
                                                    <linearGradient x1="1" y1="0" x2="0" y2="1">
                                                        <stop stop-opacity="1" stop-color="var(--bs-primary)" offset="0.3">
                                                        </stop>
                                                        <stop stop-opacity="0.6" stop-color="var(--bs-paper-bg)"
                                                            offset="0.7"></stop>
                                                        <stop stop-opacity="0.6" stop-color="var(--bs-paper-bg)" offset="1">
                                                        </stop>
                                                    </linearGradient>
                                                    <linearGradient x1="1" y1="0" x2="0" y2="1"
                                                        id="SvgjsLinearGradient1055">
                                                        <stop stop-opacity="1" stop-color="var(--bs-primary)" offset="0.3">
                                                        </stop>
                                                        <stop stop-opacity="0.6" stop-color="var(--bs-primary)"
                                                            offset="0.7"></stop>
                                                        <stop stop-opacity="0.6" stop-color="var(--bs-primary)" offset="1">
                                                        </stop>
                                                    </linearGradient>
                                                </defs>
                                                <g class="apexcharts-radialbar">
                                                    <g>
                                                        <g class="apexcharts-tracks">
                                                            <g class="apexcharts-radialbar-track apexcharts-track" rel="1">
                                                                <path
                                                                    d="M 71.80457317073167 162.4961658472277 A 66.3908536585366 66.3908536585366 0 1 1 138.1954268292683 162.49616584722773 "
                                                                    fill="none" fill-opacity="1" stroke="var(--bs-paper-bg)"
                                                                    stroke-opacity="1" stroke-linecap="butt"
                                                                    stroke-width="16.69878048780488" stroke-dasharray="0"
                                                                    class="apexcharts-radialbar-area"
                                                                    id="apexcharts-radialbarTrack-0"
                                                                    data:pathOrig="M 71.80457317073167 162.4961658472277 A 66.3908536585366 66.3908536585366 0 1 1 138.1954268292683 162.49616584722773 ">
                                                                </path>
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <g class="apexcharts-series apexcharts-radial-series"
                                                                seriesName="Growth" rel="1" data:realIndex="0">
                                                                <path
                                                                    d="M 71.80457317073167 162.4961658472277 A 66.3908536585366 66.3908536585366 0 1 1 171.02715761560546 98.0602660920455 "
                                                                    fill="none" fill-opacity="0.85"
                                                                    stroke="url(#SvgjsLinearGradient1055)"
                                                                    stroke-opacity="1" stroke-linecap="butt"
                                                                    stroke-width="16.69878048780488" stroke-dasharray="5"
                                                                    class="apexcharts-radialbar-area apexcharts-radialbar-slice-0"
                                                                    data:angle="234" data:value="78" index="0" j="0"
                                                                    data:pathOrig="M 71.80457317073167 162.4961658472277 A 66.3908536585366 66.3908536585366 0 1 1 171.02715761560546 98.0602660920455 ">
                                                                </path>
                                                            </g>
                                                            <circle r="53.04146341463416" cx="105" cy="105"
                                                                class="apexcharts-radialbar-hollow" fill="transparent">
                                                            </circle>
                                                            <g class="apexcharts-datalabels-group"
                                                                transform="translate(0, 0) scale(1)" style="opacity: 1;">
                                                                <text x="105" y="120" text-anchor="middle"
                                                                    dominant-baseline="auto" font-size="15px"
                                                                    font-family="var(--bs-font-family-base)"
                                                                    font-weight="500" fill="var(--bs-body-color)"
                                                                    class="apexcharts-text apexcharts-datalabel-label"
                                                                    style="font-family: var(--bs-font-family-base);">Growth</text><text
                                                                    x="105" y="96" text-anchor="middle"
                                                                    dominant-baseline="auto" font-size="22px"
                                                                    font-family="var(--bs-font-family-base)"
                                                                    font-weight="500" fill="var(--bs-heading-color)"
                                                                    class="apexcharts-text apexcharts-datalabel-value"
                                                                    style="font-family: var(--bs-font-family-base);">78%</text>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                                <line x1="0" y1="0" x2="210" y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs">
                                                </line>
                                                <line x1="0" y1="0" x2="210" y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="0" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs-hidden"></line>
                                            </g>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                        </svg></div>
                                </div>
                                <div class="text-center fw-medium my-6">62% Company Growth</div>

                                <div class="d-flex gap-11 justify-content-between">
                                    <div class="d-flex">
                                        <div class="avatar me-2">
                                            <span class="avatar-initial rounded-2 bg-label-primary"><i
                                                    class="icon-base bx bx-dollar icon-lg text-primary"></i></span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <small>
                                                <script>
                                                    document.write(new Date().getFullYear() - 1);
                                                </script>2024
                                            </small>
                                            <h6 class="mb-0">$32.5k</h6>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="avatar me-2">
                                            <span class="avatar-initial rounded-2 bg-label-info"><i
                                                    class="icon-base bx bx-wallet icon-lg text-info"></i></span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <small>
                                                <script>
                                                    document.write(new Date().getFullYear() - 2);
                                                </script>2023
                                            </small>
                                            <h6 class="mb-0">$41.2k</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Total Revenue -->
            <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
                <div class="row">
                    <div class="col-6 mb-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                    <div class="avatar flex-shrink-0">
                                        <img src="../../assets/img/icons/unicons/paypal.png" alt="paypal" class="rounded">
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-base bx bx-dots-vertical-rounded text-body-secondary"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-1">Payments</p>
                                <h4 class="card-title mb-3">$2,456</h4>
                                <small class="text-danger fw-medium"><i class="icon-base bx bx-down-arrow-alt"></i>
                                    -14.82%</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-6">
                        <div class="card h-100">
                            <div class="card-body pb-0">
                                <span class="d-block fw-medium mb-1">Revenue</span>
                                <h4 class="card-title mb-0 mb-lg-4">425k</h4>
                                <div id="revenueChart" style="min-height: 110px;">
                                    <div id="apexcharts70inzicv"
                                        class="apexcharts-canvas apexcharts70inzicv apexcharts-theme-"
                                        style="width: 415px; height: 95px;"><svg xmlns="http://www.w3.org/2000/svg"
                                            version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg"
                                            xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="415" height="95">
                                            <foreignObject x="0" y="0" width="415" height="95">
                                                <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml"
                                                    style="max-height: 47.5px;"></div>
                                                <style type="text/css">
                                                    .apexcharts-flip-y {
                                                        transform: scaleY(-1) translateY(-100%);
                                                        transform-origin: top;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-flip-x {
                                                        transform: scaleX(-1);
                                                        transform-origin: center;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-legend {
                                                        display: flex;
                                                        overflow: auto;
                                                        padding: 0 10px;
                                                    }

                                                    .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                        flex-direction: column;
                                                    }

                                                    .apexcharts-legend-group {
                                                        display: flex;
                                                    }

                                                    .apexcharts-legend-group-vertical {
                                                        flex-direction: column-reverse;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom,
                                                    .apexcharts-legend.apx-legend-position-top {
                                                        flex-wrap: wrap
                                                    }

                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        flex-direction: column;
                                                        bottom: 0;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        justify-content: flex-start;
                                                        align-items: flex-start;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                        justify-content: center;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                        justify-content: flex-end;
                                                        align-items: flex-end;
                                                    }

                                                    .apexcharts-legend-series {
                                                        cursor: pointer;
                                                        line-height: normal;
                                                        display: flex;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend-text {
                                                        position: relative;
                                                        font-size: 14px;
                                                    }

                                                    .apexcharts-legend-text *,
                                                    .apexcharts-legend-marker * {
                                                        pointer-events: none;
                                                    }

                                                    .apexcharts-legend-marker {
                                                        position: relative;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        cursor: pointer;
                                                        margin-right: 1px;
                                                    }

                                                    .apexcharts-legend-series.apexcharts-no-click {
                                                        cursor: auto;
                                                    }

                                                    .apexcharts-legend .apexcharts-hidden-zero-series,
                                                    .apexcharts-legend .apexcharts-hidden-null-series {
                                                        display: none !important;
                                                    }

                                                    .apexcharts-inactive-legend {
                                                        opacity: 0.45;
                                                    }
                                                </style>
                                            </foreignObject>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                            <g class="apexcharts-yaxis" rel="0" transform="translate(-8, 0)">
                                                <g class="apexcharts-yaxis-texts-g"></g>
                                            </g>
                                            <g class="apexcharts-inner apexcharts-graphical" transform="translate(0, 10)">
                                                <defs>
                                                    <linearGradient x1="0" y1="0" x2="0" y2="1"
                                                        id="SvgjsLinearGradient1056">
                                                        <stop stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)"
                                                            offset="0"></stop>
                                                        <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                                            offset="1"></stop>
                                                        <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                                            offset="1"></stop>
                                                    </linearGradient>
                                                    <clipPath id="gridRectMask70inzicv">
                                                        <rect width="409.6614580154419" height="58.269332818984985" x="0"
                                                            y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                                            stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectBarMask70inzicv">
                                                        <rect width="413.6614580154419" height="62.269332818984985" x="-2"
                                                            y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                                            stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectMarkerMask70inzicv">
                                                        <rect width="409.6614580154419" height="58.269332818984985" x="0"
                                                            y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                                            stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMask70inzicv"></clipPath>
                                                    <clipPath id="nonForecastMask70inzicv"></clipPath>
                                                </defs>
                                                <rect width="43.89229907308306" height="58.269332818984985" x="0" y="0"
                                                    rx="0" ry="0" opacity="1" stroke-width="0" stroke="#b6b6b6"
                                                    stroke-dasharray="3" fill="url(#SvgjsLinearGradient1056)"
                                                    class="apexcharts-xcrosshairs" y2="58.269332818984985" filter="none"
                                                    fill-opacity="0.9"></rect>
                                                <g class="apexcharts-grid">
                                                    <g class="apexcharts-gridlines-horizontal" style="display: none;">
                                                        <line x1="0" y1="0" x2="409.6614580154419" y2="0" stroke="#e0e0e0"
                                                            stroke-dasharray="0" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line x1="0" y1="29.134666409492493" x2="409.6614580154419"
                                                            y2="29.134666409492493" stroke="#e0e0e0" stroke-dasharray="0"
                                                            stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                        <line x1="0" y1="58.269332818984985" x2="409.6614580154419"
                                                            y2="58.269332818984985" stroke="#e0e0e0" stroke-dasharray="0"
                                                            stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                    </g>
                                                    <g class="apexcharts-gridlines-vertical" style="display: none;"></g>
                                                    <line x1="0" y1="58.269332818984985" x2="409.6614580154419"
                                                        y2="58.269332818984985" stroke="transparent" stroke-dasharray="0"
                                                        stroke-linecap="butt"></line>
                                                    <line x1="0" y1="1" x2="0" y2="58.269332818984985" stroke="transparent"
                                                        stroke-dasharray="0" stroke-linecap="butt"></line>
                                                </g>
                                                <g class="apexcharts-grid-borders" style="display: none;"></g>
                                                <g class="apexcharts-bar-series apexcharts-plot-series">
                                                    <g class="apexcharts-series" rel="1" seriesName="series-1"
                                                        data:realIndex="0">
                                                        <path
                                                            d="M 7.315383178847178 54.27033281898498 L 7.315383178847178 38.962599691390984 C 7.315383178847178 36.962599691390984 9.315383178847178 34.962599691390984 11.315383178847178 34.962599691390984 L 47.20768225193024 34.962599691390984 C 49.20768225193024 34.962599691390984 51.20768225193024 36.962599691390984 51.20768225193024 38.962599691390984 L 51.20768225193024 54.27033281898498 C 51.20768225193024 56.27033281898498 49.20768225193024 58.27033281898498 47.20768225193024 58.27033281898498 L 11.315383178847178 58.27033281898498 C 9.315383178847178 58.27033281898498 7.315383178847178 56.27033281898498 7.315383178847178 54.27033281898498 Z "
                                                            fill="var(--bs-primary-bg-subtle)" fill-opacity="1"
                                                            stroke="var(--bs-primary-bg-subtle)" stroke-opacity="1"
                                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask70inzicv)"
                                                            pathTo="M 7.315383178847178 54.27033281898498 L 7.315383178847178 38.962599691390984 C 7.315383178847178 36.962599691390984 9.315383178847178 34.962599691390984 11.315383178847178 34.962599691390984 L 47.20768225193024 34.962599691390984 C 49.20768225193024 34.962599691390984 51.20768225193024 36.962599691390984 51.20768225193024 38.962599691390984 L 51.20768225193024 54.27033281898498 C 51.20768225193024 56.27033281898498 49.20768225193024 58.27033281898498 47.20768225193024 58.27033281898498 L 11.315383178847178 58.27033281898498 C 9.315383178847178 58.27033281898498 7.315383178847178 56.27033281898498 7.315383178847178 54.27033281898498 Z "
                                                            pathFrom="M 7.315383178847178 58.27033281898498 L 7.315383178847178 58.27033281898498 L 51.20768225193024 58.27033281898498 L 51.20768225193024 58.27033281898498 L 51.20768225193024 58.27033281898498 L 51.20768225193024 58.27033281898498 L 51.20768225193024 58.27033281898498 L 7.315383178847178 58.27033281898498 Z"
                                                            cy="34.96159969139099" cx="65.83844860962459" j="0" val="40"
                                                            barHeight="23.307733127593995" barWidth="43.89229907308306">
                                                        </path>
                                                        <path
                                                            d="M 65.83844860962459 54.27033281898498 L 65.83844860962459 6.914466640949252 C 65.83844860962459 4.914466640949252 67.83844860962459 2.9144666409492523 69.83844860962459 2.9144666409492523 L 105.73074768270766 2.9144666409492523 C 107.73074768270766 2.9144666409492523 109.73074768270766 4.914466640949252 109.73074768270766 6.914466640949252 L 109.73074768270766 54.27033281898498 C 109.73074768270766 56.27033281898498 107.73074768270766 58.27033281898498 105.73074768270766 58.27033281898498 L 69.83844860962459 58.27033281898498 C 67.83844860962459 58.27033281898498 65.83844860962459 56.27033281898498 65.83844860962459 54.27033281898498 Z "
                                                            fill="var(--bs-primary-bg-subtle)" fill-opacity="1"
                                                            stroke="var(--bs-primary-bg-subtle)" stroke-opacity="1"
                                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask70inzicv)"
                                                            pathTo="M 65.83844860962459 54.27033281898498 L 65.83844860962459 6.914466640949252 C 65.83844860962459 4.914466640949252 67.83844860962459 2.9144666409492523 69.83844860962459 2.9144666409492523 L 105.73074768270766 2.9144666409492523 C 107.73074768270766 2.9144666409492523 109.73074768270766 4.914466640949252 109.73074768270766 6.914466640949252 L 109.73074768270766 54.27033281898498 C 109.73074768270766 56.27033281898498 107.73074768270766 58.27033281898498 105.73074768270766 58.27033281898498 L 69.83844860962459 58.27033281898498 C 67.83844860962459 58.27033281898498 65.83844860962459 56.27033281898498 65.83844860962459 54.27033281898498 Z "
                                                            pathFrom="M 65.83844860962459 58.27033281898498 L 65.83844860962459 58.27033281898498 L 109.73074768270766 58.27033281898498 L 109.73074768270766 58.27033281898498 L 109.73074768270766 58.27033281898498 L 109.73074768270766 58.27033281898498 L 109.73074768270766 58.27033281898498 L 65.83844860962459 58.27033281898498 Z"
                                                            cy="2.9134666409492525" cx="124.361514040402" j="1" val="95"
                                                            barHeight="55.35586617803573" barWidth="43.89229907308306">
                                                        </path>
                                                        <path
                                                            d="M 124.361514040402 54.27033281898498 L 124.361514040402 27.308733127593992 C 124.361514040402 25.308733127593992 126.361514040402 23.308733127593992 128.361514040402 23.308733127593992 L 164.25381311348505 23.308733127593992 C 166.25381311348505 23.308733127593992 168.25381311348505 25.308733127593992 168.25381311348505 27.308733127593992 L 168.25381311348505 54.27033281898498 C 168.25381311348505 56.27033281898498 166.25381311348505 58.27033281898498 164.25381311348505 58.27033281898498 L 128.361514040402 58.27033281898498 C 126.361514040402 58.27033281898498 124.361514040402 56.27033281898498 124.361514040402 54.27033281898498 Z "
                                                            fill="var(--bs-primary-bg-subtle)" fill-opacity="1"
                                                            stroke="var(--bs-primary-bg-subtle)" stroke-opacity="1"
                                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask70inzicv)"
                                                            pathTo="M 124.361514040402 54.27033281898498 L 124.361514040402 27.308733127593992 C 124.361514040402 25.308733127593992 126.361514040402 23.308733127593992 128.361514040402 23.308733127593992 L 164.25381311348505 23.308733127593992 C 166.25381311348505 23.308733127593992 168.25381311348505 25.308733127593992 168.25381311348505 27.308733127593992 L 168.25381311348505 54.27033281898498 C 168.25381311348505 56.27033281898498 166.25381311348505 58.27033281898498 164.25381311348505 58.27033281898498 L 128.361514040402 58.27033281898498 C 126.361514040402 58.27033281898498 124.361514040402 56.27033281898498 124.361514040402 54.27033281898498 Z "
                                                            pathFrom="M 124.361514040402 58.27033281898498 L 124.361514040402 58.27033281898498 L 168.25381311348505 58.27033281898498 L 168.25381311348505 58.27033281898498 L 168.25381311348505 58.27033281898498 L 168.25381311348505 58.27033281898498 L 168.25381311348505 58.27033281898498 L 124.361514040402 58.27033281898498 Z"
                                                            cy="23.30773312759399" cx="182.88457947117942" j="2" val="60"
                                                            barHeight="34.961599691390994" barWidth="43.89229907308306">
                                                        </path>
                                                        <path
                                                            d="M 182.88457947117942 54.27033281898498 L 182.88457947117942 36.04913305044174 C 182.88457947117942 34.04913305044174 184.88457947117942 32.04913305044174 186.88457947117942 32.04913305044174 L 222.77687854426247 32.04913305044174 C 224.77687854426247 32.04913305044174 226.77687854426247 34.04913305044174 226.77687854426247 36.04913305044174 L 226.77687854426247 54.27033281898498 C 226.77687854426247 56.27033281898498 224.77687854426247 58.27033281898498 222.77687854426247 58.27033281898498 L 186.88457947117942 58.27033281898498 C 184.88457947117942 58.27033281898498 182.88457947117942 56.27033281898498 182.88457947117942 54.27033281898498 Z "
                                                            fill="var(--bs-primary-bg-subtle)" fill-opacity="1"
                                                            stroke="var(--bs-primary-bg-subtle)" stroke-opacity="1"
                                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask70inzicv)"
                                                            pathTo="M 182.88457947117942 54.27033281898498 L 182.88457947117942 36.04913305044174 C 182.88457947117942 34.04913305044174 184.88457947117942 32.04913305044174 186.88457947117942 32.04913305044174 L 222.77687854426247 32.04913305044174 C 224.77687854426247 32.04913305044174 226.77687854426247 34.04913305044174 226.77687854426247 36.04913305044174 L 226.77687854426247 54.27033281898498 C 226.77687854426247 56.27033281898498 224.77687854426247 58.27033281898498 222.77687854426247 58.27033281898498 L 186.88457947117942 58.27033281898498 C 184.88457947117942 58.27033281898498 182.88457947117942 56.27033281898498 182.88457947117942 54.27033281898498 Z "
                                                            pathFrom="M 182.88457947117942 58.27033281898498 L 182.88457947117942 58.27033281898498 L 226.77687854426247 58.27033281898498 L 226.77687854426247 58.27033281898498 L 226.77687854426247 58.27033281898498 L 226.77687854426247 58.27033281898498 L 226.77687854426247 58.27033281898498 L 182.88457947117942 58.27033281898498 Z"
                                                            cy="32.04813305044174" cx="241.40764490195684" j="3" val="45"
                                                            barHeight="26.221199768543244" barWidth="43.89229907308306">
                                                        </path>
                                                        <path
                                                            d="M 241.40764490195684 54.27033281898498 L 241.40764490195684 9.827933281898499 C 241.40764490195684 7.827933281898499 243.40764490195684 5.827933281898498 245.40764490195684 5.827933281898498 L 281.2999439750399 5.827933281898498 C 283.2999439750399 5.827933281898498 285.2999439750399 7.827933281898499 285.2999439750399 9.827933281898499 L 285.2999439750399 54.27033281898498 C 285.2999439750399 56.27033281898498 283.2999439750399 58.27033281898498 281.2999439750399 58.27033281898498 L 245.40764490195684 58.27033281898498 C 243.40764490195684 58.27033281898498 241.40764490195684 56.27033281898498 241.40764490195684 54.27033281898498 Z "
                                                            fill="var(--bs-primary)" fill-opacity="1"
                                                            stroke="var(--bs-primary)" stroke-opacity="1"
                                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask70inzicv)"
                                                            pathTo="M 241.40764490195684 54.27033281898498 L 241.40764490195684 9.827933281898499 C 241.40764490195684 7.827933281898499 243.40764490195684 5.827933281898498 245.40764490195684 5.827933281898498 L 281.2999439750399 5.827933281898498 C 283.2999439750399 5.827933281898498 285.2999439750399 7.827933281898499 285.2999439750399 9.827933281898499 L 285.2999439750399 54.27033281898498 C 285.2999439750399 56.27033281898498 283.2999439750399 58.27033281898498 281.2999439750399 58.27033281898498 L 245.40764490195684 58.27033281898498 C 243.40764490195684 58.27033281898498 241.40764490195684 56.27033281898498 241.40764490195684 54.27033281898498 Z "
                                                            pathFrom="M 241.40764490195684 58.27033281898498 L 241.40764490195684 58.27033281898498 L 285.2999439750399 58.27033281898498 L 285.2999439750399 58.27033281898498 L 285.2999439750399 58.27033281898498 L 285.2999439750399 58.27033281898498 L 285.2999439750399 58.27033281898498 L 241.40764490195684 58.27033281898498 Z"
                                                            cy="5.826933281898498" cx="299.93071033273424" j="4" val="90"
                                                            barHeight="52.44239953708649" barWidth="43.89229907308306">
                                                        </path>
                                                        <path
                                                            d="M 299.93071033273424 54.27033281898498 L 299.93071033273424 33.135666409492494 C 299.93071033273424 31.135666409492494 301.93071033273424 29.135666409492494 303.93071033273424 29.135666409492494 L 339.8230094058173 29.135666409492494 C 341.8230094058173 29.135666409492494 343.8230094058173 31.135666409492494 343.8230094058173 33.135666409492494 L 343.8230094058173 54.27033281898498 C 343.8230094058173 56.27033281898498 341.8230094058173 58.27033281898498 339.8230094058173 58.27033281898498 L 303.93071033273424 58.27033281898498 C 301.93071033273424 58.27033281898498 299.93071033273424 56.27033281898498 299.93071033273424 54.27033281898498 Z "
                                                            fill="var(--bs-primary-bg-subtle)" fill-opacity="1"
                                                            stroke="var(--bs-primary-bg-subtle)" stroke-opacity="1"
                                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask70inzicv)"
                                                            pathTo="M 299.93071033273424 54.27033281898498 L 299.93071033273424 33.135666409492494 C 299.93071033273424 31.135666409492494 301.93071033273424 29.135666409492494 303.93071033273424 29.135666409492494 L 339.8230094058173 29.135666409492494 C 341.8230094058173 29.135666409492494 343.8230094058173 31.135666409492494 343.8230094058173 33.135666409492494 L 343.8230094058173 54.27033281898498 C 343.8230094058173 56.27033281898498 341.8230094058173 58.27033281898498 339.8230094058173 58.27033281898498 L 303.93071033273424 58.27033281898498 C 301.93071033273424 58.27033281898498 299.93071033273424 56.27033281898498 299.93071033273424 54.27033281898498 Z "
                                                            pathFrom="M 299.93071033273424 58.27033281898498 L 299.93071033273424 58.27033281898498 L 343.8230094058173 58.27033281898498 L 343.8230094058173 58.27033281898498 L 343.8230094058173 58.27033281898498 L 343.8230094058173 58.27033281898498 L 343.8230094058173 58.27033281898498 L 299.93071033273424 58.27033281898498 Z"
                                                            cy="29.134666409492493" cx="358.45377576351166" j="5" val="50"
                                                            barHeight="29.134666409492493" barWidth="43.89229907308306">
                                                        </path>
                                                        <path
                                                            d="M 358.45377576351166 54.27033281898498 L 358.45377576351166 18.568333204746246 C 358.45377576351166 16.568333204746246 360.45377576351166 14.568333204746247 362.45377576351166 14.568333204746247 L 398.3460748365947 14.568333204746247 C 400.3460748365947 14.568333204746247 402.3460748365947 16.568333204746246 402.3460748365947 18.568333204746246 L 402.3460748365947 54.27033281898498 C 402.3460748365947 56.27033281898498 400.3460748365947 58.27033281898498 398.3460748365947 58.27033281898498 L 362.45377576351166 58.27033281898498 C 360.45377576351166 58.27033281898498 358.45377576351166 56.27033281898498 358.45377576351166 54.27033281898498 Z "
                                                            fill="var(--bs-primary-bg-subtle)" fill-opacity="1"
                                                            stroke="var(--bs-primary-bg-subtle)" stroke-opacity="1"
                                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                            class="apexcharts-bar-area undefined" index="0"
                                                            clip-path="url(#gridRectBarMask70inzicv)"
                                                            pathTo="M 358.45377576351166 54.27033281898498 L 358.45377576351166 18.568333204746246 C 358.45377576351166 16.568333204746246 360.45377576351166 14.568333204746247 362.45377576351166 14.568333204746247 L 398.3460748365947 14.568333204746247 C 400.3460748365947 14.568333204746247 402.3460748365947 16.568333204746246 402.3460748365947 18.568333204746246 L 402.3460748365947 54.27033281898498 C 402.3460748365947 56.27033281898498 400.3460748365947 58.27033281898498 398.3460748365947 58.27033281898498 L 362.45377576351166 58.27033281898498 C 360.45377576351166 58.27033281898498 358.45377576351166 56.27033281898498 358.45377576351166 54.27033281898498 Z "
                                                            pathFrom="M 358.45377576351166 58.27033281898498 L 358.45377576351166 58.27033281898498 L 402.3460748365947 58.27033281898498 L 402.3460748365947 58.27033281898498 L 402.3460748365947 58.27033281898498 L 402.3460748365947 58.27033281898498 L 402.3460748365947 58.27033281898498 L 358.45377576351166 58.27033281898498 Z"
                                                            cy="14.567333204746248" cx="416.9768411942891" j="6" val="75"
                                                            barHeight="43.70199961423874" barWidth="43.89229907308306">
                                                        </path>
                                                        <g class="apexcharts-bar-goals-markers">
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask70inzicv)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask70inzicv)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask70inzicv)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask70inzicv)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask70inzicv)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask70inzicv)"></g>
                                                            <g className="apexcharts-bar-goals-groups"
                                                                class="apexcharts-hidden-element-shown"
                                                                clip-path="url(#gridRectMarkerMask70inzicv)"></g>
                                                        </g>
                                                        <g class="apexcharts-bar-shadows apexcharts-hidden-element-shown">
                                                        </g>
                                                    </g>
                                                    <g class="apexcharts-datalabels apexcharts-hidden-element-shown"
                                                        data:realIndex="0"></g>
                                                </g>
                                                <line x1="0" y1="0" x2="409.6614580154419" y2="0" stroke="#b6b6b6"
                                                    stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs"></line>
                                                <line x1="0" y1="0" x2="409.6614580154419" y2="0" stroke="#b6b6b6"
                                                    stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs-hidden"></line>
                                                <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                    <g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text
                                                            x="29.261532715388707" y="86.26933281898499"
                                                            text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>M</tspan>
                                                            <title>M</title>
                                                        </text><text x="87.78459814616612" y="86.26933281898499"
                                                            text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>T</tspan>
                                                            <title>T</title>
                                                        </text><text x="146.30766357694353" y="86.26933281898499"
                                                            text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>W</tspan>
                                                            <title>W</title>
                                                        </text><text x="204.83072900772095" y="86.26933281898499"
                                                            text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>T</tspan>
                                                            <title>T</title>
                                                        </text><text x="263.35379443849837" y="86.26933281898499"
                                                            text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>F</tspan>
                                                            <title>F</title>
                                                        </text><text x="321.8768598692758" y="86.26933281898499"
                                                            text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>S</tspan>
                                                            <title>S</title>
                                                        </text><text x="380.3999253000532" y="86.26933281898499"
                                                            text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>S</tspan>
                                                            <title>S</title>
                                                        </text></g>
                                                </g>
                                                <g class="apexcharts-yaxis-annotations"></g>
                                                <g class="apexcharts-xaxis-annotations"></g>
                                                <g class="apexcharts-point-annotations"></g>
                                            </g>
                                        </svg>
                                        <div class="apexcharts-tooltip apexcharts-theme-light">
                                            <div class="apexcharts-tooltip-title"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                            <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                                style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                    style="background-color: var(--bs-primary-bg-subtle);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-y-label"></span><span
                                                            class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span
                                                            class="apexcharts-tooltip-text-goals-label"></span><span
                                                            class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                            <div class="apexcharts-yaxistooltip-text"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-6">
                        <div class="card">
                            <div class="card-body">
                                <div
                                    class="d-flex justify-content-between align-items-center flex-sm-row flex-column gap-10 flex-wrap">
                                    <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                        <div class="card-title mb-6">
                                            <h5 class="text-nowrap mb-1">Profile Report</h5>
                                            <span class="badge bg-label-warning">YEAR 2022</span>
                                        </div>
                                        <div class="mt-sm-auto">
                                            <span class="text-success text-nowrap fw-medium"><i
                                                    class="icon-base bx bx-up-arrow-alt"></i> 68.2%</span>
                                            <h4 class="mb-0">$84,686k</h4>
                                        </div>
                                    </div>
                                    <div id="profileReportChart" style="min-height: 75px;">
                                        <div id="apexchartsl694ksiy"
                                            class="apexcharts-canvas apexchartsl694ksiy apexcharts-theme-"
                                            style="width: 240px; height: 75px;"><svg xmlns="http://www.w3.org/2000/svg"
                                                version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                                width="240" height="75">
                                                <foreignObject x="0" y="0" width="240" height="75">
                                                    <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml"
                                                        style="max-height: 37.5px;"></div>
                                                    <style type="text/css">
                                                        .apexcharts-flip-y {
                                                            transform: scaleY(-1) translateY(-100%);
                                                            transform-origin: top;
                                                            transform-box: fill-box;
                                                        }

                                                        .apexcharts-flip-x {
                                                            transform: scaleX(-1);
                                                            transform-origin: center;
                                                            transform-box: fill-box;
                                                        }

                                                        .apexcharts-legend {
                                                            display: flex;
                                                            overflow: auto;
                                                            padding: 0 10px;
                                                        }

                                                        .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                            flex-direction: column;
                                                        }

                                                        .apexcharts-legend-group {
                                                            display: flex;
                                                        }

                                                        .apexcharts-legend-group-vertical {
                                                            flex-direction: column-reverse;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom,
                                                        .apexcharts-legend.apx-legend-position-top {
                                                            flex-wrap: wrap
                                                        }

                                                        .apexcharts-legend.apx-legend-position-right,
                                                        .apexcharts-legend.apx-legend-position-left {
                                                            flex-direction: column;
                                                            bottom: 0;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                        .apexcharts-legend.apx-legend-position-right,
                                                        .apexcharts-legend.apx-legend-position-left {
                                                            justify-content: flex-start;
                                                            align-items: flex-start;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                            justify-content: center;
                                                            align-items: center;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                        .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                            justify-content: flex-end;
                                                            align-items: flex-end;
                                                        }

                                                        .apexcharts-legend-series {
                                                            cursor: pointer;
                                                            line-height: normal;
                                                            display: flex;
                                                            align-items: center;
                                                        }

                                                        .apexcharts-legend-text {
                                                            position: relative;
                                                            font-size: 14px;
                                                        }

                                                        .apexcharts-legend-text *,
                                                        .apexcharts-legend-marker * {
                                                            pointer-events: none;
                                                        }

                                                        .apexcharts-legend-marker {
                                                            position: relative;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            cursor: pointer;
                                                            margin-right: 1px;
                                                        }

                                                        .apexcharts-legend-series.apexcharts-no-click {
                                                            cursor: auto;
                                                        }

                                                        .apexcharts-legend .apexcharts-hidden-zero-series,
                                                        .apexcharts-legend .apexcharts-hidden-null-series {
                                                            display: none !important;
                                                        }

                                                        .apexcharts-inactive-legend {
                                                            opacity: 0.45;
                                                        }
                                                    </style>
                                                </foreignObject>
                                                <rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1"
                                                    stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe">
                                                </rect>
                                                <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)">
                                                </g>
                                                <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)">
                                                </g>
                                                <g class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g>
                                                <g class="apexcharts-inner apexcharts-graphical"
                                                    transform="translate(0, 2.5)">
                                                    <defs>
                                                        <clipPath id="gridRectMaskl694ksiy">
                                                            <rect width="232" height="70" x="0" y="0" rx="0" ry="0"
                                                                opacity="1" stroke-width="0" stroke="none"
                                                                stroke-dasharray="0" fill="#fff"></rect>
                                                        </clipPath>
                                                        <clipPath id="gridRectBarMaskl694ksiy">
                                                            <rect width="241" height="79" x="-4.5" y="-4.5" rx="0" ry="0"
                                                                opacity="1" stroke-width="0" stroke="none"
                                                                stroke-dasharray="0" fill="#fff"></rect>
                                                        </clipPath>
                                                        <clipPath id="gridRectMarkerMaskl694ksiy">
                                                            <rect width="232" height="70" x="0" y="0" rx="0" ry="0"
                                                                opacity="1" stroke-width="0" stroke="none"
                                                                stroke-dasharray="0" fill="#fff"></rect>
                                                        </clipPath>
                                                        <clipPath id="forecastMaskl694ksiy"></clipPath>
                                                        <clipPath id="nonForecastMaskl694ksiy"></clipPath>
                                                        <filter id="SvgjsFilter1064" filterUnits="userSpaceOnUse"
                                                            width="200%" height="200%" x="-50%" y="-50%">
                                                            <feOffset id="SvgjsFeOffset1057" result="offset"
                                                                in="SourceGraphic" dx="5" dy="10"></feOffset>
                                                            <feGaussianBlur id="SvgjsFeGaussianBlur1058" result="blur"
                                                                in="offset" stdDeviation="3"></feGaussianBlur>
                                                            <feFlood id="SvgjsFeFlood1059" result="flood" in="SourceGraphic"
                                                                flood-color="var(--bs-warning)" flood-opacity="0.15">
                                                            </feFlood>
                                                            <feComposite id="SvgjsFeComposite1060" result="shadow"
                                                                in="flood" in2="blur" operator="in"></feComposite>
                                                            <feMerge id="SvgjsFeMerge1061" result="SvgjsFeMerge1061"
                                                                in="SourceGraphic">
                                                                <feMergeNode id="SvgjsFeMergeNode1062"
                                                                    result="SvgjsFeMergeNode1062" in="shadow"></feMergeNode>
                                                                <feMergeNode id="SvgjsFeMergeNode1063"
                                                                    result="SvgjsFeMergeNode1063" in="SourceGraphic">
                                                                </feMergeNode>
                                                            </feMerge>
                                                        </filter>
                                                    </defs>
                                                    <line x1="0" y1="0" x2="0" y2="70" stroke="#b6b6b6" stroke-dasharray="3"
                                                        stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0"
                                                        width="1" height="70" fill="#b1b9c4" filter="none"
                                                        fill-opacity="0.9" stroke-width="1"></line>
                                                    <g class="apexcharts-grid">
                                                        <g class="apexcharts-gridlines-horizontal" style="display: none;">
                                                            <line x1="0" y1="0" x2="232" y2="0" stroke="#e0e0e0"
                                                                stroke-dasharray="0" stroke-linecap="butt"
                                                                class="apexcharts-gridline"></line>
                                                            <line x1="0" y1="35" x2="232" y2="35" stroke="#e0e0e0"
                                                                stroke-dasharray="0" stroke-linecap="butt"
                                                                class="apexcharts-gridline"></line>
                                                            <line x1="0" y1="70" x2="232" y2="70" stroke="#e0e0e0"
                                                                stroke-dasharray="0" stroke-linecap="butt"
                                                                class="apexcharts-gridline"></line>
                                                        </g>
                                                        <g class="apexcharts-gridlines-vertical" style="display: none;"></g>
                                                        <line x1="0" y1="70" x2="232" y2="70" stroke="transparent"
                                                            stroke-dasharray="0" stroke-linecap="butt"></line>
                                                        <line x1="0" y1="1" x2="0" y2="70" stroke="transparent"
                                                            stroke-dasharray="0" stroke-linecap="butt"></line>
                                                    </g>
                                                    <g class="apexcharts-grid-borders" style="display: none;"></g>
                                                    <g class="apexcharts-line-series apexcharts-plot-series">
                                                        <g class="apexcharts-series" zIndex="0" seriesName="series-1"
                                                            data:longestSeries="true" rel="1" data:realIndex="0">
                                                            <path
                                                                d="M 0 66.5C 16.24 66.5 30.16 10.5 46.4 10.5C 62.64 10.5 76.56 54.25 92.8 54.25C 109.03999999999999 54.25 122.96 19.25 139.2 19.25C 155.44 19.25 169.35999999999999 33.25 185.6 33.25C 201.83999999999997 33.25 215.76 5.25 231.99999999999997 5.25"
                                                                fill="none" fill-opacity="1" stroke="var(--bs-warning)"
                                                                stroke-opacity="1" stroke-linecap="butt" stroke-width="5"
                                                                stroke-dasharray="0" class="apexcharts-line" index="0"
                                                                clip-path="url(#gridRectMaskl694ksiy)"
                                                                filter="url(#SvgjsFilter1064)"
                                                                pathTo="M 0 66.5C 16.24 66.5 30.16 10.5 46.4 10.5C 62.64 10.5 76.56 54.25 92.8 54.25C 109.03999999999999 54.25 122.96 19.25 139.2 19.25C 155.44 19.25 169.35999999999999 33.25 185.6 33.25C 201.83999999999997 33.25 215.76 5.25 231.99999999999997 5.25"
                                                                pathFrom="M 0 70 L 0 70 L 46.4 70 L 92.8 70 L 139.2 70 L 185.6 70 L 231.99999999999997 70"
                                                                fill-rule="evenodd"></path>
                                                            <g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown"
                                                                data:realIndex="0">
                                                                <g class="apexcharts-series-markers">
                                                                    <path d="M 0, 0 
               m -0, 0 
               a 0,0 0 1,0 0,0 
               a 0,0 0 1,0 -0,0" fill="var(--bs-warning)" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                        stroke-linecap="butt" stroke-width="2"
                                                                        stroke-dasharray="0" cx="0" cy="0" shape="circle"
                                                                        class="apexcharts-marker wuqccenua no-pointer-events"
                                                                        default-marker-size="0"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g class="apexcharts-datalabels" data:realIndex="0"></g>
                                                    </g>
                                                    <line x1="0" y1="0" x2="232" y2="0" stroke="#b6b6b6"
                                                        stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                                        class="apexcharts-ycrosshairs"></line>
                                                    <line x1="0" y1="0" x2="232" y2="0" stroke="#b6b6b6"
                                                        stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                                        class="apexcharts-ycrosshairs-hidden"></line>
                                                    <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                        <g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)">
                                                        </g>
                                                    </g>
                                                    <g class="apexcharts-yaxis-annotations"></g>
                                                    <g class="apexcharts-xaxis-annotations"></g>
                                                    <g class="apexcharts-point-annotations"></g>
                                                </g>
                                            </svg>
                                            <div class="apexcharts-tooltip apexcharts-theme-light">
                                                <div class="apexcharts-tooltip-title"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                </div>
                                                <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                                    style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                        style="background-color: var(--bs-warning);"></span>
                                                    <div class="apexcharts-tooltip-text"
                                                        style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                        <div class="apexcharts-tooltip-y-group"><span
                                                                class="apexcharts-tooltip-text-y-label"></span><span
                                                                class="apexcharts-tooltip-text-y-value"></span></div>
                                                        <div class="apexcharts-tooltip-goals-group"><span
                                                                class="apexcharts-tooltip-text-goals-label"></span><span
                                                                class="apexcharts-tooltip-text-goals-value"></span></div>
                                                        <div class="apexcharts-tooltip-z-group"><span
                                                                class="apexcharts-tooltip-text-z-label"></span><span
                                                                class="apexcharts-tooltip-text-z-value"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                                <div class="apexcharts-yaxistooltip-text"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="mb-1 me-2">Order Statistics</h5>
                            <p class="card-subtitle">42.82k Total Sales</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn text-body-secondary p-0" type="button" id="orederStatistics"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-base bx bx-dots-vertical-rounded icon-lg"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-6">
                            <div class="d-flex flex-column align-items-center gap-1">
                                <h3 class="mb-1">8,258</h3>
                                <small>Total Orders</small>
                            </div>
                            <div id="orderStatisticsChart" style="min-height: 118px;">
                                <div id="apexchartsrdhk6yo7" class="apexcharts-canvas apexchartsrdhk6yo7 apexcharts-theme-"
                                    style="width: 136px; height: 118px;"><svg xmlns="http://www.w3.org/2000/svg"
                                        version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg"
                                        xmlns:data="ApexChartsNS" transform="translate(15, 0)" width="136" height="118">
                                        <foreignObject x="0" y="0" width="136" height="118">
                                            <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml"></div>
                                            <style type="text/css">
                                                .apexcharts-flip-y {
                                                    transform: scaleY(-1) translateY(-100%);
                                                    transform-origin: top;
                                                    transform-box: fill-box;
                                                }

                                                .apexcharts-flip-x {
                                                    transform: scaleX(-1);
                                                    transform-origin: center;
                                                    transform-box: fill-box;
                                                }

                                                .apexcharts-legend {
                                                    display: flex;
                                                    overflow: auto;
                                                    padding: 0 10px;
                                                }

                                                .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                    flex-direction: column;
                                                }

                                                .apexcharts-legend-group {
                                                    display: flex;
                                                }

                                                .apexcharts-legend-group-vertical {
                                                    flex-direction: column-reverse;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom,
                                                .apexcharts-legend.apx-legend-position-top {
                                                    flex-wrap: wrap
                                                }

                                                .apexcharts-legend.apx-legend-position-right,
                                                .apexcharts-legend.apx-legend-position-left {
                                                    flex-direction: column;
                                                    bottom: 0;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                .apexcharts-legend.apx-legend-position-right,
                                                .apexcharts-legend.apx-legend-position-left {
                                                    justify-content: flex-start;
                                                    align-items: flex-start;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                    justify-content: center;
                                                    align-items: center;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                    justify-content: flex-end;
                                                    align-items: flex-end;
                                                }

                                                .apexcharts-legend-series {
                                                    cursor: pointer;
                                                    line-height: normal;
                                                    display: flex;
                                                    align-items: center;
                                                }

                                                .apexcharts-legend-text {
                                                    position: relative;
                                                    font-size: 14px;
                                                }

                                                .apexcharts-legend-text *,
                                                .apexcharts-legend-marker * {
                                                    pointer-events: none;
                                                }

                                                .apexcharts-legend-marker {
                                                    position: relative;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    cursor: pointer;
                                                    margin-right: 1px;
                                                }

                                                .apexcharts-legend-series.apexcharts-no-click {
                                                    cursor: auto;
                                                }

                                                .apexcharts-legend .apexcharts-hidden-zero-series,
                                                .apexcharts-legend .apexcharts-hidden-null-series {
                                                    display: none !important;
                                                }

                                                .apexcharts-inactive-legend {
                                                    opacity: 0.45;
                                                }
                                            </style>
                                        </foreignObject>
                                        <g class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0)">
                                            <defs>
                                                <clipPath id="gridRectMaskrdhk6yo7">
                                                    <rect width="121" height="165" x="0" y="0" rx="0" ry="0" opacity="1"
                                                        stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff">
                                                    </rect>
                                                </clipPath>
                                                <clipPath id="gridRectBarMaskrdhk6yo7">
                                                    <rect width="130" height="174" x="-4.5" y="-4.5" rx="0" ry="0"
                                                        opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                                        fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="gridRectMarkerMaskrdhk6yo7">
                                                    <rect width="121" height="165" x="0" y="0" rx="0" ry="0" opacity="1"
                                                        stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff">
                                                    </rect>
                                                </clipPath>
                                                <clipPath id="forecastMaskrdhk6yo7"></clipPath>
                                                <clipPath id="nonForecastMaskrdhk6yo7"></clipPath>
                                            </defs>
                                            <g class="apexcharts-pie">
                                                <g transform="translate(0, 0) scale(1)">
                                                    <circle r="37.518292682926834" cx="60.5" cy="60.5" fill="transparent">
                                                    </circle>
                                                    <g class="apexcharts-slices">
                                                        <g class="apexcharts-series apexcharts-pie-series"
                                                            seriesName="Electronic" rel="1" data:realIndex="0">
                                                            <path
                                                                d="M 60.5 10.475609756097555 A 50.024390243902445 50.024390243902445 0 0 1 110.52439024390245 60.5 L 98.01829268292684 60.5 A 37.518292682926834 37.518292682926834 0 0 0 60.5 22.981707317073166 L 60.5 10.475609756097555 z "
                                                                fill="var(--bs-success)" fill-opacity="1"
                                                                stroke="var(--bs-paper-bg)" stroke-opacity="1"
                                                                stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                                class="apexcharts-pie-area apexcharts-donut-slice-0"
                                                                index="0" j="0" data:angle="90" data:startAngle="0"
                                                                data:strokeWidth="5" data:value="50"
                                                                data:pathOrig="M 60.5 10.475609756097555 A 50.024390243902445 50.024390243902445 0 0 1 110.52439024390245 60.5 L 98.01829268292684 60.5 A 37.518292682926834 37.518292682926834 0 0 0 60.5 22.981707317073166 L 60.5 10.475609756097555 z ">
                                                            </path>
                                                        </g>
                                                        <g class="apexcharts-series apexcharts-pie-series"
                                                            seriesName="Sports" rel="2" data:realIndex="1">
                                                            <path
                                                                d="M 110.52439024390245 60.5 A 50.024390243902445 50.024390243902445 0 0 1 15.92794192413799 83.21059792599539 L 27.07095644310349 77.53294844449654 A 37.518292682926834 37.518292682926834 0 0 0 98.01829268292684 60.5 L 110.52439024390245 60.5 z "
                                                                fill="var(--bs-primary)" fill-opacity="1"
                                                                stroke="var(--bs-paper-bg)" stroke-opacity="1"
                                                                stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                                class="apexcharts-pie-area apexcharts-donut-slice-1"
                                                                index="0" j="1" data:angle="153" data:startAngle="90"
                                                                data:strokeWidth="5" data:value="85"
                                                                data:pathOrig="M 110.52439024390245 60.5 A 50.024390243902445 50.024390243902445 0 0 1 15.92794192413799 83.21059792599539 L 27.07095644310349 77.53294844449654 A 37.518292682926834 37.518292682926834 0 0 0 98.01829268292684 60.5 L 110.52439024390245 60.5 z ">
                                                            </path>
                                                        </g>
                                                        <g class="apexcharts-series apexcharts-pie-series"
                                                            seriesName="Decor" rel="3" data:realIndex="2">
                                                            <path
                                                                d="M 15.92794192413799 83.21059792599539 A 50.024390243902445 50.024390243902445 0 0 1 12.923977684844871 45.04161328138981 L 24.817983263633657 48.90620996104236 A 37.518292682926834 37.518292682926834 0 0 0 27.07095644310349 77.53294844449654 L 15.92794192413799 83.21059792599539 z "
                                                                fill="var(--bs-secondary)" fill-opacity="1"
                                                                stroke="var(--bs-paper-bg)" stroke-opacity="1"
                                                                stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                                class="apexcharts-pie-area apexcharts-donut-slice-2"
                                                                index="0" j="2" data:angle="45" data:startAngle="243"
                                                                data:strokeWidth="5" data:value="25"
                                                                data:pathOrig="M 15.92794192413799 83.21059792599539 A 50.024390243902445 50.024390243902445 0 0 1 12.923977684844871 45.04161328138981 L 24.817983263633657 48.90620996104236 A 37.518292682926834 37.518292682926834 0 0 0 27.07095644310349 77.53294844449654 L 15.92794192413799 83.21059792599539 z ">
                                                            </path>
                                                        </g>
                                                        <g class="apexcharts-series apexcharts-pie-series"
                                                            seriesName="Fashion" rel="4" data:realIndex="3">
                                                            <path
                                                                d="M 12.923977684844871 45.04161328138981 A 50.024390243902445 50.024390243902445 0 0 1 60.491269096883734 10.475610518012587 L 60.4934518226628 22.98170788850944 A 37.518292682926834 37.518292682926834 0 0 0 24.817983263633657 48.90620996104236 L 12.923977684844871 45.04161328138981 z "
                                                                fill="var(--bs-info)" fill-opacity="1"
                                                                stroke="var(--bs-paper-bg)" stroke-opacity="1"
                                                                stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                                class="apexcharts-pie-area apexcharts-donut-slice-3"
                                                                index="0" j="3" data:angle="72" data:startAngle="288"
                                                                data:strokeWidth="5" data:value="40"
                                                                data:pathOrig="M 12.923977684844871 45.04161328138981 A 50.024390243902445 50.024390243902445 0 0 1 60.491269096883734 10.475610518012587 L 60.4934518226628 22.98170788850944 A 37.518292682926834 37.518292682926834 0 0 0 24.817983263633657 48.90620996104236 L 12.923977684844871 45.04161328138981 z ">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                                <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)">
                                                    <text x="60.5" y="77.5" text-anchor="middle" dominant-baseline="auto"
                                                        font-size="13px" font-family="Helvetica, Arial, sans-serif"
                                                        font-weight="400" fill="var(--bs-body-color)"
                                                        class="apexcharts-text apexcharts-datalabel-label"
                                                        style="font-family: Helvetica, Arial, sans-serif;">Weekly</text><text
                                                        x="60.5" y="59.5" text-anchor="middle" dominant-baseline="auto"
                                                        font-size="1.125rem" font-family="var(--bs-font-family-base)"
                                                        font-weight="500" fill="var(--bs-heading-color)"
                                                        class="apexcharts-text apexcharts-datalabel-value"
                                                        style="font-family: var(--bs-font-family-base);">38%</text></g>
                                            </g>
                                            <line x1="0" y1="0" x2="121" y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs">
                                            </line>
                                            <line x1="0" y1="0" x2="121" y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                stroke-width="0" stroke-linecap="butt"
                                                class="apexcharts-ycrosshairs-hidden"></line>
                                        </g>
                                        <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                    </svg>
                                    <div class="apexcharts-tooltip apexcharts-theme-dark">
                                        <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                            style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                style="background-color: var(--bs-success);"></span>
                                            <div class="apexcharts-tooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                        <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-1"
                                            style="order: 2;"><span class="apexcharts-tooltip-marker"
                                                style="background-color: var(--bs-primary);"></span>
                                            <div class="apexcharts-tooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                        <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-2"
                                            style="order: 3;"><span class="apexcharts-tooltip-marker"
                                                style="background-color: var(--bs-secondary);"></span>
                                            <div class="apexcharts-tooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                        <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-3"
                                            style="order: 4;"><span class="apexcharts-tooltip-marker"
                                                style="background-color: var(--bs-info);"></span>
                                            <div class="apexcharts-tooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="p-0 m-0">
                            <li class="d-flex align-items-center mb-5">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="icon-base bx bx-mobile-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Electronic</h6>
                                        <small>Mobile, Earbuds, TV</small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="mb-0">82.5k</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-center mb-5">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-success"><i
                                            class="icon-base bx bx-closet"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Fashion</h6>
                                        <small>T-shirt, Jeans, Shoes</small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="mb-0">23.8k</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-center mb-5">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                            class="icon-base bx bx-home-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Decor</h6>
                                        <small>Fine Art, Dining</small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="mb-0">849k</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-secondary"><i
                                            class="icon-base bx bx-football"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Sports</h6>
                                        <small>Football, Cricket Kit</small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="mb-0">99</h6>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Order Statistics -->

            <!-- Expense Overview -->
            <div class="col-md-6 col-lg-4 order-1 mb-6">
                <div class="card h-100">
                    <div class="card-header nav-align-top">
                        <ul class="nav nav-pills flex-wrap row-gap-2" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-tabs-line-card-income" aria-controls="navs-tabs-line-card-income"
                                    aria-selected="true">Income</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" aria-selected="false"
                                    tabindex="-1">Expenses</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" aria-selected="false"
                                    tabindex="-1">Profit</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0">
                            <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                                <div class="d-flex mb-6">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <img src="../../assets/img/icons/unicons/wallet-primary.png" alt="User">
                                    </div>
                                    <div>
                                        <p class="mb-0">Total Balance</p>
                                        <div class="d-flex align-items-center">
                                            <h6 class="mb-0 me-1">$459.10</h6>
                                            <small class="text-success fw-medium">
                                                <i class="icon-base bx bx-chevron-up icon-lg"></i>
                                                42.9%
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div id="incomeChart" style="min-height: 200px;">
                                    <div id="apexchartsymjo0qn2"
                                        class="apexcharts-canvas apexchartsymjo0qn2 apexcharts-theme-"
                                        style="width: 252px; height: 200px;"><svg xmlns="http://www.w3.org/2000/svg"
                                            version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS"
                                            transform="translate(0, 0)" width="252" height="200">
                                            <foreignObject x="0" y="0" width="252" height="200">
                                                <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml"
                                                    style="max-height: 100px;"></div>
                                                <style type="text/css">
                                                    .apexcharts-flip-y {
                                                        transform: scaleY(-1) translateY(-100%);
                                                        transform-origin: top;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-flip-x {
                                                        transform: scaleX(-1);
                                                        transform-origin: center;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-legend {
                                                        display: flex;
                                                        overflow: auto;
                                                        padding: 0 10px;
                                                    }

                                                    .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                        flex-direction: column;
                                                    }

                                                    .apexcharts-legend-group {
                                                        display: flex;
                                                    }

                                                    .apexcharts-legend-group-vertical {
                                                        flex-direction: column-reverse;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom,
                                                    .apexcharts-legend.apx-legend-position-top {
                                                        flex-wrap: wrap
                                                    }

                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        flex-direction: column;
                                                        bottom: 0;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                    .apexcharts-legend.apx-legend-position-right,
                                                    .apexcharts-legend.apx-legend-position-left {
                                                        justify-content: flex-start;
                                                        align-items: flex-start;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                        justify-content: center;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                    .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                        justify-content: flex-end;
                                                        align-items: flex-end;
                                                    }

                                                    .apexcharts-legend-series {
                                                        cursor: pointer;
                                                        line-height: normal;
                                                        display: flex;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend-text {
                                                        position: relative;
                                                        font-size: 14px;
                                                    }

                                                    .apexcharts-legend-text *,
                                                    .apexcharts-legend-marker * {
                                                        pointer-events: none;
                                                    }

                                                    .apexcharts-legend-marker {
                                                        position: relative;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        cursor: pointer;
                                                        margin-right: 1px;
                                                    }

                                                    .apexcharts-legend-series.apexcharts-no-click {
                                                        cursor: auto;
                                                    }

                                                    .apexcharts-legend .apexcharts-hidden-zero-series,
                                                    .apexcharts-legend .apexcharts-hidden-null-series {
                                                        display: none !important;
                                                    }

                                                    .apexcharts-inactive-legend {
                                                        opacity: 0.45;
                                                    }
                                                </style>
                                            </foreignObject>
                                            <rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1"
                                                stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                            <g class="apexcharts-yaxis" rel="0" transform="translate(-8, 0)">
                                                <g class="apexcharts-yaxis-texts-g"></g>
                                            </g>
                                            <g class="apexcharts-inner apexcharts-graphical" transform="translate(10, 10)">
                                                <defs>
                                                    <clipPath id="gridRectMaskymjo0qn2">
                                                        <rect width="224.6875" height="159.269332818985" x="0" y="0" rx="0"
                                                            ry="0" opacity="1" stroke-width="0" stroke="none"
                                                            stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectBarMaskymjo0qn2">
                                                        <rect width="231.6875" height="166.269332818985" x="-3.5" y="-3.5"
                                                            rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                                            stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectMarkerMaskymjo0qn2">
                                                        <rect width="238.6875" height="173.269332818985" x="-7" y="-7"
                                                            rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                                            stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMaskymjo0qn2"></clipPath>
                                                    <clipPath id="nonForecastMaskymjo0qn2"></clipPath>
                                                    <linearGradient x1="0" y1="0" x2="0" y2="1"
                                                        id="SvgjsLinearGradient1065">
                                                        <stop stop-opacity="0.3" stop-color="var(--bs-primary)" offset="0">
                                                        </stop>
                                                        <stop stop-opacity="0.3" stop-color="var(--bs-paper-bg)" offset="1">
                                                        </stop>
                                                        <stop stop-opacity="0.3" stop-color="var(--bs-paper-bg)" offset="1">
                                                        </stop>
                                                    </linearGradient>
                                                </defs>
                                                <line x1="0" y1="0" x2="0" y2="159.269332818985" stroke="#b6b6b6"
                                                    stroke-dasharray="3" stroke-linecap="butt"
                                                    class="apexcharts-xcrosshairs" x="0" y="0" width="1"
                                                    height="159.269332818985" fill="#b1b9c4" filter="none"
                                                    fill-opacity="0.9" stroke-width="1"></line>
                                                <g class="apexcharts-grid">
                                                    <g class="apexcharts-gridlines-horizontal">
                                                        <line x1="0" y1="39.81733320474625" x2="224.6875"
                                                            y2="39.81733320474625" stroke="var(--bs-border-color)"
                                                            stroke-dasharray="8" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line x1="0" y1="79.6346664094925" x2="224.6875"
                                                            y2="79.6346664094925" stroke="var(--bs-border-color)"
                                                            stroke-dasharray="8" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line x1="0" y1="119.45199961423874" x2="224.6875"
                                                            y2="119.45199961423874" stroke="var(--bs-border-color)"
                                                            stroke-dasharray="8" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                    </g>
                                                    <g class="apexcharts-gridlines-vertical"></g>
                                                    <line x1="0" y1="159.269332818985" x2="224.6875" y2="159.269332818985"
                                                        stroke="transparent" stroke-dasharray="0" stroke-linecap="butt">
                                                    </line>
                                                    <line x1="0" y1="1" x2="0" y2="159.269332818985" stroke="transparent"
                                                        stroke-dasharray="0" stroke-linecap="butt"></line>
                                                </g>
                                                <g class="apexcharts-grid-borders">
                                                    <line x1="0" y1="0" x2="224.6875" y2="0" stroke="var(--bs-border-color)"
                                                        stroke-dasharray="8" stroke-linecap="butt"
                                                        class="apexcharts-gridline"></line>
                                                    <line x1="0" y1="159.269332818985" x2="224.6875" y2="159.269332818985"
                                                        stroke="var(--bs-border-color)" stroke-dasharray="8"
                                                        stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                </g>
                                                <g class="apexcharts-area-series apexcharts-plot-series">
                                                    <g class="apexcharts-series" zIndex="0" seriesName="series-1"
                                                        data:longestSeries="true" rel="1" data:realIndex="0">
                                                        <path
                                                            d="M 0 115.47026629376411C 13.106770833333332 115.47026629376411 24.341145833333332 79.6346664094925 37.447916666666664 79.6346664094925C 50.5546875 79.6346664094925 61.7890625 111.4885329732895 74.89583333333333 111.4885329732895C 88.00260416666666 111.4885329732895 99.23697916666667 31.853866563797 112.34375 31.853866563797C 125.45052083333333 31.853866563797 136.68489583333331 95.561599691391 149.79166666666666 95.561599691391C 162.8984375 95.561599691391 174.1328125 59.725999807119365 187.23958333333334 59.725999807119365C 200.34635416666669 59.725999807119365 211.58072916666669 83.61639972996711 224.6875 83.61639972996711C 224.6875 83.61639972996711 224.6875 83.61639972996711 224.6875 159.269332818985 L 0 159.269332818985z"
                                                            fill="url(#SvgjsLinearGradient1065)" fill-opacity="1"
                                                            stroke="none" stroke-opacity="1" stroke-linecap="butt"
                                                            stroke-width="0" stroke-dasharray="0" class="apexcharts-area"
                                                            index="0" clip-path="url(#gridRectMaskymjo0qn2)"
                                                            pathTo="M 0 115.47026629376411C 13.106770833333332 115.47026629376411 24.341145833333332 79.6346664094925 37.447916666666664 79.6346664094925C 50.5546875 79.6346664094925 61.7890625 111.4885329732895 74.89583333333333 111.4885329732895C 88.00260416666666 111.4885329732895 99.23697916666667 31.853866563797 112.34375 31.853866563797C 125.45052083333333 31.853866563797 136.68489583333331 95.561599691391 149.79166666666666 95.561599691391C 162.8984375 95.561599691391 174.1328125 59.725999807119365 187.23958333333334 59.725999807119365C 200.34635416666669 59.725999807119365 211.58072916666669 83.61639972996711 224.6875 83.61639972996711C 224.6875 83.61639972996711 224.6875 83.61639972996711 224.6875 159.269332818985 L 0 159.269332818985z"
                                                            pathFrom="M 0 159.269332818985 L 0 159.269332818985 L 37.447916666666664 159.269332818985 L 74.89583333333333 159.269332818985 L 112.34375 159.269332818985 L 149.79166666666666 159.269332818985 L 187.23958333333334 159.269332818985 L 224.6875 159.269332818985z">
                                                        </path>
                                                        <path
                                                            d="M 0 115.47026629376411C 13.106770833333332 115.47026629376411 24.341145833333332 79.6346664094925 37.447916666666664 79.6346664094925C 50.5546875 79.6346664094925 61.7890625 111.4885329732895 74.89583333333333 111.4885329732895C 88.00260416666666 111.4885329732895 99.23697916666667 31.853866563797 112.34375 31.853866563797C 125.45052083333333 31.853866563797 136.68489583333331 95.561599691391 149.79166666666666 95.561599691391C 162.8984375 95.561599691391 174.1328125 59.725999807119365 187.23958333333334 59.725999807119365C 200.34635416666669 59.725999807119365 211.58072916666669 83.61639972996711 224.6875 83.61639972996711"
                                                            fill="none" fill-opacity="1" stroke="var(--bs-primary)"
                                                            stroke-opacity="1" stroke-linecap="butt" stroke-width="3"
                                                            stroke-dasharray="0" class="apexcharts-area" index="0"
                                                            clip-path="url(#gridRectMaskymjo0qn2)"
                                                            pathTo="M 0 115.47026629376411C 13.106770833333332 115.47026629376411 24.341145833333332 79.6346664094925 37.447916666666664 79.6346664094925C 50.5546875 79.6346664094925 61.7890625 111.4885329732895 74.89583333333333 111.4885329732895C 88.00260416666666 111.4885329732895 99.23697916666667 31.853866563797 112.34375 31.853866563797C 125.45052083333333 31.853866563797 136.68489583333331 95.561599691391 149.79166666666666 95.561599691391C 162.8984375 95.561599691391 174.1328125 59.725999807119365 187.23958333333334 59.725999807119365C 200.34635416666669 59.725999807119365 211.58072916666669 83.61639972996711 224.6875 83.61639972996711"
                                                            pathFrom="M 0 159.269332818985 L 0 159.269332818985 L 37.447916666666664 159.269332818985 L 74.89583333333333 159.269332818985 L 112.34375 159.269332818985 L 149.79166666666666 159.269332818985 L 187.23958333333334 159.269332818985 L 224.6875 159.269332818985"
                                                            fill-rule="evenodd"></path>
                                                        <g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown"
                                                            data:realIndex="0">
                                                            <g class="" clip-path="url(#gridRectMarkerMaskymjo0qn2)">
                                                                <path d="M -1, 115.47026629376411 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="transparent" fill-opacity="1" stroke="transparent" stroke-opacity="0.9"
                                                                    stroke-linecap="butt" stroke-width="4"
                                                                    stroke-dasharray="0" cx="-1" cy="115.47026629376411"
                                                                    shape="circle"
                                                                    class="apexcharts-marker no-pointer-events wmj6g9zx3"
                                                                    rel="0" j="0" index="0" default-marker-size="6"></path>
                                                                <path d="M 36.447916666666664, 79.6346664094925 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="transparent" fill-opacity="1" stroke="transparent" stroke-opacity="0.9"
                                                                    stroke-linecap="butt" stroke-width="4"
                                                                    stroke-dasharray="0" cx="36.447916666666664"
                                                                    cy="79.6346664094925" shape="circle"
                                                                    class="apexcharts-marker no-pointer-events w1tasj32r"
                                                                    rel="1" j="1" index="0" default-marker-size="6"></path>
                                                            </g>
                                                            <g class="" clip-path="url(#gridRectMarkerMaskymjo0qn2)">
                                                                <path d="M 73.89583333333333, 111.4885329732895 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="transparent" fill-opacity="1" stroke="transparent" stroke-opacity="0.9"
                                                                    stroke-linecap="butt" stroke-width="4"
                                                                    stroke-dasharray="0" cx="73.89583333333333"
                                                                    cy="111.4885329732895" shape="circle"
                                                                    class="apexcharts-marker no-pointer-events wxd0qji4d"
                                                                    rel="2" j="2" index="0" default-marker-size="6"></path>
                                                            </g>
                                                            <g class="" clip-path="url(#gridRectMarkerMaskymjo0qn2)">
                                                                <path d="M 111.34375, 31.853866563797 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="transparent" fill-opacity="1" stroke="transparent" stroke-opacity="0.9"
                                                                    stroke-linecap="butt" stroke-width="4"
                                                                    stroke-dasharray="0" cx="111.34375" cy="31.853866563797"
                                                                    shape="circle"
                                                                    class="apexcharts-marker no-pointer-events w0p0ccv9q"
                                                                    rel="3" j="3" index="0" default-marker-size="6"></path>
                                                            </g>
                                                            <g class="" clip-path="url(#gridRectMarkerMaskymjo0qn2)">
                                                                <path d="M 148.79166666666666, 95.561599691391 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="transparent" fill-opacity="1" stroke="transparent" stroke-opacity="0.9"
                                                                    stroke-linecap="butt" stroke-width="4"
                                                                    stroke-dasharray="0" cx="148.79166666666666"
                                                                    cy="95.561599691391" shape="circle"
                                                                    class="apexcharts-marker no-pointer-events w8wtxbtuni"
                                                                    rel="4" j="4" index="0" default-marker-size="6"></path>
                                                            </g>
                                                            <g class="" clip-path="url(#gridRectMarkerMaskymjo0qn2)">
                                                                <path d="M 186.23958333333334, 59.725999807119365 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="transparent" fill-opacity="1" stroke="transparent" stroke-opacity="0.9"
                                                                    stroke-linecap="butt" stroke-width="4"
                                                                    stroke-dasharray="0" cx="186.23958333333334"
                                                                    cy="59.725999807119365" shape="circle"
                                                                    class="apexcharts-marker no-pointer-events wh27ocwd7"
                                                                    rel="5" j="5" index="0" default-marker-size="6"></path>
                                                            </g>
                                                            <g class="" clip-path="url(#gridRectMarkerMaskymjo0qn2)">
                                                                <path d="M 223.6875, 83.61639972996711 
               m -6, 0 
               a 6,6 0 1,0 12,0 
               a 6,6 0 1,0 -12,0" fill="var(--bs-white)" fill-opacity="1" stroke="var(--bs-primary)" stroke-opacity="0.9"
                                                                    stroke-linecap="butt" stroke-width="4"
                                                                    stroke-dasharray="0" cx="223.6875"
                                                                    cy="83.61639972996711" shape="circle"
                                                                    class="apexcharts-marker no-pointer-events wnhv78nql"
                                                                    rel="6" j="6" index="0" default-marker-size="6"></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <g class="apexcharts-datalabels" data:realIndex="0"></g>
                                                </g>
                                                <line x1="0" y1="0" x2="224.6875" y2="0" stroke="#b6b6b6"
                                                    stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs"></line>
                                                <line x1="0" y1="0" x2="224.6875" y2="0" stroke="#b6b6b6"
                                                    stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs-hidden"></line>
                                                <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                    <g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text
                                                            x="0" y="187.269332818985" text-anchor="middle"
                                                            dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>Jan</tspan>
                                                            <title>Jan</title>
                                                        </text><text x="37.44791666666667" y="187.269332818985"
                                                            text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>Feb</tspan>
                                                            <title>Feb</title>
                                                        </text><text x="74.89583333333333" y="187.269332818985"
                                                            text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>Mar</tspan>
                                                            <title>Mar</title>
                                                        </text><text x="112.34374999999999" y="187.269332818985"
                                                            text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>Apr</tspan>
                                                            <title>Apr</title>
                                                        </text><text x="149.79166666666663" y="187.269332818985"
                                                            text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>May</tspan>
                                                            <title>May</title>
                                                        </text><text x="187.2395833333333" y="187.269332818985"
                                                            text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>Jun</tspan>
                                                            <title>Jun</title>
                                                        </text><text x="224.68749999999994" y="187.269332818985"
                                                            text-anchor="middle" dominant-baseline="auto" font-size="13px"
                                                            font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                            fill="var(--bs-secondary-color)"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>Jul</tspan>
                                                            <title>Jul</title>
                                                        </text></g>
                                                </g>
                                                <g class="apexcharts-yaxis-annotations"></g>
                                                <g class="apexcharts-xaxis-annotations"></g>
                                                <g class="apexcharts-point-annotations"></g>
                                            </g>
                                            <rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1"
                                                stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"
                                                class="apexcharts-zoom-rect"></rect>
                                            <rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1"
                                                stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"
                                                class="apexcharts-selection-rect"></rect>
                                        </svg>
                                        <div class="apexcharts-tooltip apexcharts-theme-light">
                                            <div class="apexcharts-tooltip-title"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                            <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                                style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                    style="background-color: var(--bs-primary);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-y-label"></span><span
                                                            class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span
                                                            class="apexcharts-tooltip-text-goals-label"></span><span
                                                            class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-light">
                                            <div class="apexcharts-xaxistooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                        </div>
                                        <div
                                            class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                            <div class="apexcharts-yaxistooltip-text"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center mt-6 gap-3">
                                    <div class="flex-shrink-0">
                                        <div id="expensesOfWeek" style="min-height: 58px;">
                                            <div id="apexcharts31q8n3ak"
                                                class="apexcharts-canvas apexcharts31q8n3ak apexcharts-theme-"
                                                style="width: 60px; height: 58px;"><svg xmlns="http://www.w3.org/2000/svg"
                                                    version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    class="apexcharts-svg" xmlns:data="ApexChartsNS"
                                                    transform="translate(0, 0)" width="60" height="58">
                                                    <foreignObject x="0" y="0" width="60" height="58">
                                                        <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml">
                                                        </div>
                                                        <style type="text/css">
                                                            .apexcharts-flip-y {
                                                                transform: scaleY(-1) translateY(-100%);
                                                                transform-origin: top;
                                                                transform-box: fill-box;
                                                            }

                                                            .apexcharts-flip-x {
                                                                transform: scaleX(-1);
                                                                transform-origin: center;
                                                                transform-box: fill-box;
                                                            }

                                                            .apexcharts-legend {
                                                                display: flex;
                                                                overflow: auto;
                                                                padding: 0 10px;
                                                            }

                                                            .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                                flex-direction: column;
                                                            }

                                                            .apexcharts-legend-group {
                                                                display: flex;
                                                            }

                                                            .apexcharts-legend-group-vertical {
                                                                flex-direction: column-reverse;
                                                            }

                                                            .apexcharts-legend.apx-legend-position-bottom,
                                                            .apexcharts-legend.apx-legend-position-top {
                                                                flex-wrap: wrap
                                                            }

                                                            .apexcharts-legend.apx-legend-position-right,
                                                            .apexcharts-legend.apx-legend-position-left {
                                                                flex-direction: column;
                                                                bottom: 0;
                                                            }

                                                            .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                            .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                            .apexcharts-legend.apx-legend-position-right,
                                                            .apexcharts-legend.apx-legend-position-left {
                                                                justify-content: flex-start;
                                                                align-items: flex-start;
                                                            }

                                                            .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                            .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                                justify-content: center;
                                                                align-items: center;
                                                            }

                                                            .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                            .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                                justify-content: flex-end;
                                                                align-items: flex-end;
                                                            }

                                                            .apexcharts-legend-series {
                                                                cursor: pointer;
                                                                line-height: normal;
                                                                display: flex;
                                                                align-items: center;
                                                            }

                                                            .apexcharts-legend-text {
                                                                position: relative;
                                                                font-size: 14px;
                                                            }

                                                            .apexcharts-legend-text *,
                                                            .apexcharts-legend-marker * {
                                                                pointer-events: none;
                                                            }

                                                            .apexcharts-legend-marker {
                                                                position: relative;
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                cursor: pointer;
                                                                margin-right: 1px;
                                                            }

                                                            .apexcharts-legend-series.apexcharts-no-click {
                                                                cursor: auto;
                                                            }

                                                            .apexcharts-legend .apexcharts-hidden-zero-series,
                                                            .apexcharts-legend .apexcharts-hidden-null-series {
                                                                display: none !important;
                                                            }

                                                            .apexcharts-inactive-legend {
                                                                opacity: 0.45;
                                                            }
                                                        </style>
                                                    </foreignObject>
                                                    <g class="apexcharts-inner apexcharts-graphical"
                                                        transform="translate(-10, -10)">
                                                        <defs>
                                                            <clipPath id="gridRectMask31q8n3ak">
                                                                <rect width="80" height="85" x="0" y="0" rx="0" ry="0"
                                                                    opacity="1" stroke-width="0" stroke="none"
                                                                    stroke-dasharray="0" fill="#fff"></rect>
                                                            </clipPath>
                                                            <clipPath id="gridRectBarMask31q8n3ak">
                                                                <rect width="86" height="91" x="-3" y="-3" rx="0" ry="0"
                                                                    opacity="1" stroke-width="0" stroke="none"
                                                                    stroke-dasharray="0" fill="#fff"></rect>
                                                            </clipPath>
                                                            <clipPath id="gridRectMarkerMask31q8n3ak">
                                                                <rect width="80" height="85" x="0" y="0" rx="0" ry="0"
                                                                    opacity="1" stroke-width="0" stroke="none"
                                                                    stroke-dasharray="0" fill="#fff"></rect>
                                                            </clipPath>
                                                            <clipPath id="forecastMask31q8n3ak"></clipPath>
                                                            <clipPath id="nonForecastMask31q8n3ak"></clipPath>
                                                        </defs>
                                                        <g class="apexcharts-radialbar">
                                                            <g>
                                                                <g class="apexcharts-tracks">
                                                                    <g class="apexcharts-radialbar-track apexcharts-track"
                                                                        rel="1">
                                                                        <path
                                                                            d="M 40 19.336585365853654 A 20.663414634146346 20.663414634146346 0 1 1 39.9963935538176 19.336585680575453 "
                                                                            fill="none" fill-opacity="1"
                                                                            stroke="var(--bs-border-color)"
                                                                            stroke-opacity="1" stroke-linecap="round"
                                                                            stroke-width="4.760097560975613"
                                                                            stroke-dasharray="0"
                                                                            class="apexcharts-radialbar-area"
                                                                            id="apexcharts-radialbarTrack-0"
                                                                            data:pathOrig="M 40 19.336585365853654 A 20.663414634146346 20.663414634146346 0 1 1 39.9963935538176 19.336585680575453 ">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                                <g>
                                                                    <g class="apexcharts-series apexcharts-radial-series"
                                                                        seriesName="series-1" rel="1" data:realIndex="0">
                                                                        <path
                                                                            d="M 40 19.336585365853654 A 20.663414634146346 20.663414634146346 0 1 1 23.28294639915962 52.1456503839557 "
                                                                            fill="none" fill-opacity="0.85"
                                                                            stroke="var(--bs-primary)" stroke-opacity="1"
                                                                            stroke-linecap="round"
                                                                            stroke-width="4.907317073170734"
                                                                            stroke-dasharray="0"
                                                                            class="apexcharts-radialbar-area apexcharts-radialbar-slice-0"
                                                                            data:angle="234" data:value="65" index="0" j="0"
                                                                            data:pathOrig="M 40 19.336585365853654 A 20.663414634146346 20.663414634146346 0 1 1 23.28294639915962 52.1456503839557 ">
                                                                        </path>
                                                                    </g>
                                                                    <circle r="16.283365853658538" cx="40" cy="40"
                                                                        class="apexcharts-radialbar-hollow"
                                                                        fill="transparent"></circle>
                                                                    <g class="apexcharts-datalabels-group"
                                                                        transform="translate(0, 0) scale(1)"
                                                                        style="opacity: 1;"><text x="40" y="45"
                                                                            text-anchor="middle" dominant-baseline="auto"
                                                                            font-size="12px"
                                                                            font-family="var(--bs-font-family-base)"
                                                                            font-weight="400" fill="var(--bs-body-color)"
                                                                            class="apexcharts-text apexcharts-datalabel-value"
                                                                            style="font-family: var(--bs-font-family-base);">$65</text>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <line x1="0" y1="0" x2="80" y2="0" stroke="#b6b6b6"
                                                            stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                                            class="apexcharts-ycrosshairs"></line>
                                                        <line x1="0" y1="0" x2="80" y2="0" stroke="#b6b6b6"
                                                            stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                                            class="apexcharts-ycrosshairs-hidden"></line>
                                                    </g>
                                                    <g class="apexcharts-datalabels-group"
                                                        transform="translate(0, 0) scale(1)"></g>
                                                </svg></div>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Income this week</h6>
                                        <small>$39k less than last week</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Expense Overview -->

            <!-- Transactions -->
            <div class="col-md-6 col-lg-4 order-2 mb-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Transactions</h5>
                        <div class="dropdown">
                            <button class="btn text-body-secondary p-0" type="button" id="transactionID"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-base bx bx-dots-vertical-rounded icon-lg"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                                <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <ul class="p-0 m-0">
                            <li class="d-flex align-items-center mb-6">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="../../assets/img/icons/unicons/paypal.png" alt="User" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="d-block">Paypal</small>
                                        <h6 class="fw-normal mb-0">Send money</h6>
                                    </div>
                                    <div class="user-progress d-flex align-items-center gap-2">
                                        <h6 class="fw-normal mb-0">+82.6</h6>
                                        <span class="text-body-secondary">USD</span>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-center mb-6">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="../../assets/img/icons/unicons/wallet.png" alt="User" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="d-block">Wallet</small>
                                        <h6 class="fw-normal mb-0">Mac'D</h6>
                                    </div>
                                    <div class="user-progress d-flex align-items-center gap-2">
                                        <h6 class="fw-normal mb-0">+270.69</h6>
                                        <span class="text-body-secondary">USD</span>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-center mb-6">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="../../assets/img/icons/unicons/chart.png" alt="User" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="d-block">Transfer</small>
                                        <h6 class="fw-normal mb-0">Refund</h6>
                                    </div>
                                    <div class="user-progress d-flex align-items-center gap-2">
                                        <h6 class="fw-normal mb-0">+637.91</h6>
                                        <span class="text-body-secondary">USD</span>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-center mb-6">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="../../assets/img/icons/unicons/cc-primary.png" alt="User" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="d-block">Credit Card</small>
                                        <h6 class="fw-normal mb-0">Ordered Food</h6>
                                    </div>
                                    <div class="user-progress d-flex align-items-center gap-2">
                                        <h6 class="fw-normal mb-0">-838.71</h6>
                                        <span class="text-body-secondary">USD</span>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-center mb-6">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="../../assets/img/icons/unicons/wallet.png" alt="User" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="d-block">Wallet</small>
                                        <h6 class="fw-normal mb-0">Starbucks</h6>
                                    </div>
                                    <div class="user-progress d-flex align-items-center gap-2">
                                        <h6 class="fw-normal mb-0">+203.33</h6>
                                        <span class="text-body-secondary">USD</span>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="../../assets/img/icons/unicons/cc-warning.png" alt="User" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="d-block">Mastercard</small>
                                        <h6 class="fw-normal mb-0">Ordered Food</h6>
                                    </div>
                                    <div class="user-progress d-flex align-items-center gap-2">
                                        <h6 class="fw-normal mb-0">-92.45</h6>
                                        <span class="text-body-secondary">USD</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Transactions -->
            <!-- Activity Timeline -->
            <div class="col-md-12 col-lg-6 order-4 order-lg-3">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title m-0 me-2">Activity Timeline</h5>
                        <div class="dropdown">
                            <button class="btn text-body-secondary p-0" type="button" id="timelineWapper"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-base bx bx-dots-vertical-rounded icon-lg"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="timelineWapper">
                                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <ul class="timeline mb-0">
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-primary"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-3">
                                        <h6 class="mb-0">12 Invoices have been paid</h6>
                                        <small class="text-body-secondary">12 min ago</small>
                                    </div>
                                    <p class="mb-2">Invoices have been paid to the company</p>
                                    <div class="d-flex align-items-center mb-1">
                                        <div class="badge bg-lighter rounded-2">
                                            <img src="../../assets//img/icons/misc/pdf.png" alt="img" width="15"
                                                class="me-2">
                                            <span class="h6 mb-0 text-body">invoices.pdf</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-success"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-3">
                                        <h6 class="mb-0">Client Meeting</h6>
                                        <small class="text-body-secondary">45 min ago</small>
                                    </div>
                                    <p class="mb-2">Project meeting with john @10:15am</p>
                                    <div class="d-flex justify-content-between flex-wrap gap-2">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <img src="../../assets/img/avatars/1.png" alt="Avatar"
                                                    class="rounded-circle">
                                            </div>
                                            <div>
                                                <p class="mb-0 small fw-medium">Lester McCarthy (Client)</p>
                                                <small>CEO of ThemeSelection</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-info"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-3">
                                        <h6 class="mb-0">Create a new project for client</h6>
                                        <small class="text-body-secondary">2 Day Ago</small>
                                    </div>
                                    <p class="mb-2">6 team members in a project</p>
                                    <ul class="list-group list-group-flush">
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center flex-wrap p-0">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <ul
                                                    class="list-unstyled users-list d-flex align-items-center avatar-group m-0 me-2">
                                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                        data-bs-placement="top" class="avatar pull-up"
                                                        aria-label="Vinnie Mostowy" data-bs-original-title="Vinnie Mostowy">
                                                        <img class="rounded-circle" src="../../assets/img/avatars/5.png"
                                                            alt="Avatar">
                                                    </li>
                                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                        data-bs-placement="top" class="avatar pull-up"
                                                        aria-label="Allen Rieske" data-bs-original-title="Allen Rieske">
                                                        <img class="rounded-circle" src="../../assets/img/avatars/12.png"
                                                            alt="Avatar">
                                                    </li>
                                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                        data-bs-placement="top" class="avatar pull-up"
                                                        aria-label="Julee Rossignol"
                                                        data-bs-original-title="Julee Rossignol">
                                                        <img class="rounded-circle" src="../../assets/img/avatars/6.png"
                                                            alt="Avatar">
                                                    </li>
                                                    <li class="avatar">
                                                        <span class="avatar-initial rounded-circle pull-up text-heading"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            data-bs-original-title="3 more">+3</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Activity Timeline -->
            <!-- pill table -->
            <div class="col-md-6 order-3 order-lg-4 mb-6 mb-lg-0">
                <div class="card text-center h-100">
                    <div class="card-header nav-align-top">
                        <ul class="nav nav-pills flex-wrap row-gap-2" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-browser" aria-controls="navs-pills-browser"
                                    aria-selected="true">Browser</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-os" aria-controls="navs-pills-os" aria-selected="false"
                                    tabindex="-1">Operating System</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-country" aria-controls="navs-pills-country"
                                    aria-selected="false" tabindex="-1">Country</button>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content pt-0 pb-4">
                        <div class="tab-pane fade show active" id="navs-pills-browser" role="tabpanel">
                            <div class="table-responsive text-start text-nowrap">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Browser</th>
                                            <th>Visits</th>
                                            <th class="w-50">Data In Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/img/icons/brands/chrome.png" alt="Chrome"
                                                        height="24" class="me-3">
                                                    <span class="text-heading">Chrome</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">8.92k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: 64.75%" aria-valuenow="64.75" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">64.75%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/img/icons/brands/safari.png" alt="Safari"
                                                        height="24" class="me-3">
                                                    <span class="text-heading">Safari</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">1.29k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-primary" role="progressbar"
                                                            style="width: 18.43%" aria-valuenow="18.43" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">18.43%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/img/icons/brands/firefox.png" alt="Firefox"
                                                        height="24" class="me-3">
                                                    <span class="text-heading">Firefox</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">328</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 8.37%" aria-valuenow="8.37" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">8.37%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/img/icons/brands/edge.png" alt="Edge" height="24"
                                                        class="me-3">
                                                    <span class="text-heading">Edge</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">142</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            style="width: 6.12%" aria-valuenow="6.12" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">6.12%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/img/icons/brands/opera.png" alt="Opera"
                                                        height="24" class="me-3">
                                                    <span class="text-heading">Opera</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">82</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                            style="width: 2.12%" aria-valuenow="1.94" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">2.12%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/img/icons/brands/uc.png" alt="uc" height="24"
                                                        class="me-3">
                                                    <span class="text-heading">UC Browser</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">328</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                            style="width: 20.14%" aria-valuenow="1.94" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">20.14%</small>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-pills-os" role="tabpanel">
                            <div class="table-responsive text-start text-nowrap">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>System</th>
                                            <th>Visits</th>
                                            <th class="w-50">Data In Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/img/icons/brands/windows.png" alt="Windows"
                                                        height="24" class="me-3">
                                                    <span class="text-heading">Windows</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">875.24k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: 61.50%" aria-valuenow="61.50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">61.50%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/img/icons/brands/mac.png" alt="Mac" height="24"
                                                        class="me-3">
                                                    <span class="text-heading">Mac</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">89.68k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-primary" role="progressbar"
                                                            style="width: 16.67%" aria-valuenow="16.67" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">16.67%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/img/icons/brands/ubuntu.png" alt="Ubuntu"
                                                        height="24" class="me-3">
                                                    <span class="text-heading">Ubuntu</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">37.68k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 12.82%" aria-valuenow="12.82" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">12.82%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/img/icons/brands/chrome.png" alt="Chrome"
                                                        height="24" class="me-3">
                                                    <span class="text-heading">Chrome</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">8.34k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            style="width: 6.25%" aria-valuenow="6.25" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">6.25%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/img/icons/brands/cent.png" alt="Cent" height="24"
                                                        class="me-3">
                                                    <span class="text-heading">Cent</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">2.25k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                            style="width: 2.76%" aria-valuenow="2.76" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">2.76%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/img/icons/brands/linux.png" alt="linux"
                                                        height="24" class="me-3">
                                                    <span class="text-heading">Linux</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">328k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                            style="width: 20.14%" aria-valuenow="2.76" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">20.14%</small>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-pills-country" role="tabpanel">
                            <div class="table-responsive text-start text-nowrap">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Country</th>
                                            <th>Visits</th>
                                            <th class="w-50">Data In Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fis fi fi-us rounded-circle fs-4 me-3"></i>
                                                    <span class="text-heading">USA</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">87.24k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: 38.12%" aria-valuenow="38.12" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">38.12%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fis fi fi-br rounded-circle fs-4 me-3"></i>
                                                    <span class="text-heading">Brazil</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">42.68k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-primary" role="progressbar"
                                                            style="width: 28.23%" aria-valuenow="28.23" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">28.23%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fis fi fi-in rounded-circle fs-4 me-3"></i>
                                                    <span class="text-heading">India</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">12.58k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 14.82%" aria-valuenow="14.82" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">14.82%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fis fi fi-au rounded-circle fs-4 me-3"></i>
                                                    <span class="text-heading">Australia</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">4.13k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            style="width: 12.72%" aria-valuenow="12.72" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">12.72%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fis fi fi-fr rounded-circle fs-4 me-3"></i>
                                                    <span class="text-heading">France</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">2.21k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                            style="width: 7.11%" aria-valuenow="7.11" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">7.11%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fis fi fi-ca rounded-circle fs-4 me-3"></i>
                                                    <span class="text-heading">Canada</span>
                                                </div>
                                            </td>
                                            <td class="text-heading">22.35k</td>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center gap-4">
                                                    <div class="progress w-100" style="height:10px;">
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                            style="width: 15.13%" aria-valuenow="7.11" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="fw-medium">15.13%</small>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ pill table -->
        </div>

    </div>
@endsection