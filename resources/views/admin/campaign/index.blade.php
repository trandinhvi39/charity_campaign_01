@extends('admin.master')
@section('title')
    {{ trans('campaign.title') }}
@endsection
@section('content')
    <div class="card">
        <div class="header">
            <h2>
                {{ trans('campaign.panel_head.index') }}
                <a href="{{ URL::action('CampaignController@create') }}"
                   class="btn btn-success btn-lg waves-effect">
                    {{ trans('campaign.button.create') }}
                </a>
            </h2>
        </div>
        <div class="body table-responsive">
        @include('layouts.error')
        @include('layouts.message')
        <!-- CAMPAIGN SEARCH FORM -->
        {{ Form::open(['route' => 'admin.campaign.index', 'method' => 'GET', 'class' => 'form-inline']) }}
        <div class="card">
            <div class="header bg-cyan" id="search-text">
                <h5>
                    {{ trans('campaign.label.search') }}
                    <a href="{{ route('admin.campaign.index') }}"
                       class="header-dropdown m-r--5 btn bg-red btn-xs waves-effect">
                        {{ trans('campaign.button.reset_search') }}
                    </a>
                </h5>
            </div>
            <div class="body" id="form-search">
                <div class="row clearfix">

                    <!-- NAME -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <b>{{ trans('campaign.label.name') }}</b>
                            <div class="form-line">
                                {{
                                    Form::text('name', isset($input['name']) ? $input['name'] : "", [
                                        'class' => 'form-control',
                                        'id' => 'name',
                                    ])
                                }}
                            </div>
                        </div>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <b>{{ trans('campaign.label.description') }}</b>
                            <div class="form-line">
                                {{
                                    Form::text('description', isset($input['description']) ? $input['description'] : "", [
                                        'class' => 'form-control',
                                        'id' => 'description',
                                    ])
                                }}
                            </div>
                        </div>
                    </div>

                    <!-- ADDRESS -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <b>{{ trans('campaign.label.address') }}</b>
                            <div class="form-line">
                                {{
                                    Form::text('address', isset($input['address']) ? $input['address'] : "", [
                                        'class' => 'form-control',
                                        'id' => 'address',
                                    ])
                                }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-4 col-lg-offset-4">
                        <button class="btn bg-cyan btn-block btn-lg waves-effect">
                            {{ trans('campaign.button.search') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
        <!--END CAMPAIGN SEARCH FORM -->

            @if ($campaigns->count())
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>{{ trans('campaign.label.STT') }}</th>
                        <th>{{ trans('campaign.label.name') }}</th>
                        <th>{{ trans('campaign.label.description') }}</th>
                        <th>{{ trans('campaign.label.image') }}</th>
                        <th>{{ trans('campaign.label.address') }}</th>
                        <th>{{ trans('campaign.label.start_time') }}</th>
                        <th>{{ trans('campaign.label.end_time') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($campaigns as $campaign)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $campaign->name }}</td>
                            <td>{{ $campaign->description }}</td>
                            <td><img src="{{ $campaign->image->image }}" class="img-campaign"></td>
                            <td>{{ $campaign->address }}</td>
                            <td>{{ $campaign->start_time }}</td>
                            <td>{{ $campaign->end_time }}</td>
                            <td>
                            {{
                                Form::open([
                                    'route' => ['admin.campaign.destroy', $campaign->id],
                                    'method' => 'DELETE',
                                    'onsubmit' => 'return confirmDelete("' . trans('campaign.message.confirm_delete') . '")',
                                ])
                            }}

                                <!-- BUTTON EDIT USER -->
                                <a href="{{ URL::action('CampaignController@show', $campaign->id) }}"
                                   class="btn bg-orange btn-xs" data-toggle="tooltip" data-placement="top"
                                   title="" data-original-title="{{ trans('campaign.tooltip.show') }}">
                                    <i class="material-icons">forward</i>
                                </a>

                                <!-- BUTTON DELETE POLL -->
                                {{
                                    Form::button('<i class="material-icons">delete</i>', [
                                        'type' => 'submit',
                                        'class' => 'btn bg-red btn-xs',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'top',
                                        'title' => '',
                                        'data-original-title' => trans('campaign.tooltip.delete'),
                                        'onclick' => 'return confirm("' . trans('label.confirm_delete') . '")'
                                    ])
                                }}
                            {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="dataTables_info">
                    {{
                        trans_choice('label.paginations', $campaigns->total(), [
                            'start' => $campaigns->firstItem(),
                            'finish' => $campaigns->lastItem(),
                            'numberOfRecords' => $campaigns->total()
                        ])
                    }}
                </div>
                <div class="pagination pagination-lg">
                    {{ (isset($linkFilter) ? $linkFilter : $campaigns->render()) }}
                </div>
            @else
                <div class="card">
                    <div class="body bg-light-blue">
                        {{ trans('campaign.message.not_found_campaigns') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
