@extends('admin.layouts.app')
@section('content')

<style>
    .widget {
        background-color: #fff;
        padding: 30px;
    }
    .activity {
        position: relative;
    }
    .activity .activity-items {
        position: relative;
    }
    .activity .activity-items:before {
        content: "";
        position: absolute;
        top: 0px;
        bottom: 0px;
        left: 35px;
        width: 0px;
        border-left: 3px dashed #ddd;
    }
    .activity .activity-item-wrap {
        padding: 7px 0px 7px 70px;
        position: relative;
    }
    .activity .activity-item-wrap h4.activity-date {
        font-size: 18px;
        margin: 10px 0 0 0;
        color: #aaa;
    }
    .activity .activity-item-wrap .activity-item-badge {
        width: 30px;
        height: 30px;
        background: #315782;
        color: #fff;
        border-radius: 50%;
        position: absolute;
        top: 17px;
        left: 19px;
        text-align: center;
        border: 3px solid #fff;
        box-shadow: 0px 2px 3px #ccc;
    }
    .activity .activity-item-wrap .activity-item-badge i {
        line-height: 30px;
    }
    .activity .activity-item-wrap .activity-item {
        border-radius: 3px;
        position: relative;
        border: 1px solid #cbd6e2;
        background: #f5f8fa;
        padding: 10px 20px 10px 60px;
    }
    .activity .activity-item-wrap .activity-item:hover {
        box-shadow: 0 0 12px #cbd6e2;
        -webkit-transition: all 0.2s ease-in;
        -o-transition: all 0.2s ease-in;
        transition: all 0.2s ease-in;
    }
    .activity .activity-item-wrap .activity-item p {
        margin: 0px;
    }
    .activity .activity-item-wrap .activity-item .activity-item-meta {
        margin-bottom: 10px;
    }
    .activity .activity-item-wrap .activity-item .activity-item-meta .activity-user {
        background: #d4edda;
        height: 40px;
        width: 40px;
        border-radius: 50%;
        position: absolute;
        left: 10px;
        margin-right: 10px;
        color: #155724;
        text-align: center;
        line-height: 40px;
        font-size: 13px;
    }
    .activity .activity-item-wrap .activity-item .activity-item-meta .activity-user img {
        margin-top: -3px;
    }
    .activity .activity-item-wrap .activity-item .activity-item-meta .activity-user.activity-ismember {
        background: #cce5ff;
        color: #004085;
    }
    .activity .activity-item-wrap .activity-item .activity-item-meta .activity-user.activity-isaro {
        background: #fff;
        color: red;
        border: 1px solid #ddd;
    }
    .activity .activity-item-wrap .activity-item .activity-item-meta .activity-summary {
        font-size: 13px;
    }
    .activity .activity-item-wrap .activity-item .activity-item-meta .activity-timestamp {
        font-size: 11px;
        color: #aaa;
    }
</style>

<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Activity Logs')}}</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="widget">
                <div class="widget-content">
                    <div class="column-wrap">
                        <div class="coloumn">
                            <div class="activity">

                                @php
                                $activityLogsGrouped = $user->activityLogs->groupBy(function ($item) {
                                    return $item->created_at->format('F, Y');
                                });
                                @endphp

                                @forelse ($activityLogsGrouped as $monthYear => $activities)
                                <div class="activity-items">
                                    <div class="activity-item-wrap activity-date">
                                        <h4 class="activity-date">{{ $monthYear }}</h4>
                                    </div>

                                    @foreach ($activityLogs as $activity)
                                    <!-- start activity -->
                                    <div class="activity-item-wrap activity-call">
                                        <div class="activity-item-badge">
                                            <i class="aroicon-action-send-message"></i>
                                        </div>
                                        <div class="activity-item">
                                            <div class="activity-item-meta">
                                                <div class="activity-user activity-ismember">
                                                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                                </div>
                                                <p class="activity-summary">
                                                    <strong>
                                                        {{ $user->first_name.' '.$user->last_name }}
                                                    </strong> {{ $activity->description }}
                                                </p>
                                                <p class="activity-timestamp">
                                                    {{ $activity->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <div class="activity-item-details"></div>
                                        </div>
                                    </div>
                                    <!-- end activity -->
                                    @endforeach

                                </div>
                                <div class="mt-5">
                                    {{ $activityLogs->links() }}
                                </div>
                                @empty
                                <div class="row">
                                    <h5 class="mt-4">No data found</h5>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
