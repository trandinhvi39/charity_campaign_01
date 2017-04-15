@extends('layouts.app')

@section('js')
    @parent
    {{ Html::script('js/follow_user.js') }}
    <script type="text/javascript">
        $(document).ready(function () {
            var follow = new Follow(
                    '{{ action('FollowController@followOrUnFollowUser') }}',
                    '{{ trans('user.follow') }}',
                    '{{ trans('user.un_follow') }}'
            );
            follow.init();
        });
    </script>
@stop

@section('content')
    <div id="page-content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                @if ($tags)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('campaign.tag.name') }}</th>
                                <th>{{ trans('campaign.tag.count_campaigns') }}</th>
                            </tr>
                        </thead>
                        @foreach ($tags as $key => $tag)
                            <tr>
                                <td>{{ $key }}</td>
                                <td><a href="{{ URL::action('CampaignController@campaignWithTags', $tag['tag']) }}">{{ $tag['tag'] }}</a></td>
                                <td><span class="badge">{{ $tag['count'] }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                {{ $tags->render() }}
                @endif
            </div>

        </div>
    </div>
@stop
