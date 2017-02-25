<div class="col-lg-10 col-lg-offset-1">
    <div id="image-preview">
        <label for="image-upload" id="image-label">{{ trans('campaign.image') }}</label>
        {{ Form::file('image[]', ['class' => 'form-control', 'id' => 'image-upload']) }}
        @if ($errors->has('image[]'))
            <span class="help-block">
                <strong>{{ $errors->first('image[]') }}</strong>
            </span>
        @endif
    </div>
</div>
