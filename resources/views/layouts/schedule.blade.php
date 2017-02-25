<div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
    <label for="start_date" class="col-md-3 control-label">{{ trans('event.start_date') }}</label>

    <div class="col-md-8">
        {!! Form::text('start_date', null, ['class' => 'form-control datetimepicker', 'placeholder' => trans('event.validate.start_date.start_date') ]) !!}

        @if ($errors->has('start_date'))
            <span class="help-block">
                <strong>{{ $errors->first('start_date') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
    <label for="end_date" class="col-md-3 control-label">{{ trans('event.end_date') }}</label>

    <div class="col-md-8">
        {!! Form::text('end_date', null, ['class' => 'form-control datetimepicker', 'placeholder' => trans('event.validate.end_date.end_date')]) !!}

        @if ($errors->has('end_date'))
            <span class="help-block">
                <strong>{{ $errors->first('end_date') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('schedule_name') ? ' has-error' : '' }}">
    <label for="schedule_name" class="col-md-3 control-label">{{ trans('event.schedule_name') }}</label>

    <div class="col-md-8">
        {!! Form::text('schedule_name', old('schedule_name[]'), ['class' => 'form-control']) !!}

        @if ($errors->has('schedule_name'))
            <span class="help-block">
                <strong>{{ $errors->first('schedule_name') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('schedule_description') ? ' has-error' : '' }}">
    <label for="schedule_description" class="col-md-3 control-label">{{ trans('event.schedule_description') }}</label>
    <br>
    <div class="col-lg-10 col-lg-offset-1">
        {!! Form::textarea('schedule_description', old('schedule_description'), ['class' => 'form-control', 'id' => 'editor', 'rows' => '10']) !!}
        @if ($errors->has('schedule_description'))
            <span class="help-block">
                <strong>{{ $errors->first('schedule_description') }}</strong>
            </span>
        @endif
    </div>
</div>
