@extends('admin.master')
@section('title')
    {{ trans('user.title') }}
@endsection
@section('content')
    <div class="card">
        <div class="header">
            <h2>
                {{ trans('user.panel_head.create') }}
            </h2>
        </div>
        <div class="body">
            @include('layouts.error')
            @include('layouts.message')
            {{
                Form::open([
                    'route' => 'admin.user.store',
                    'method' => 'POST',
                    'id' => 'form_create_user',
                    'enctype' => 'multipart/form-data',
                ])
            }}
                <!-- FULL NAME -->
                <div class="form-group">
                    {{ Form::label(trans('user.label_for.name'), trans('user.label.name') . trans('user.label.required')) }}
                    <div class="form-line">
                        {{
                            Form::text('name', null, [
                                'class' => 'form-control',
                                'id' => trans('user.label_for.name'),
                                'placeholder' => trans('user.placeholder.name'),
                                'required' => true,
                                'maxlength' => config('settings.length_user.name'),
                            ])
                        }}
                    </div>
                </div>

                <!-- EMAIL -->
                {{ Form::label(trans('user.label_for.email'), trans('user.label.email') . trans('user.label.required')) }}
                <div class="form-group">
                    <div class="form-line">
                        {{
                            Form::email('email', null, [
                                'class' => 'form-control',
                                'id' => trans('user.label_for.email'),
                                'placeholder' => trans('user.placeholder.email'),
                                'required' => true,
                                'maxlength' => config('settings.length_user.email'),
                            ])
                        }}
                    </div>
                </div>

                <!-- PHONE NUMBER -->
                {{ Form::label(trans('user.label_for.phone_number'), trans('user.label.phone_number')) }}
                <div class="form-group">
                    <div class="form-line">
                        {{
                            Form::text('phone_number', null, [
                                'class' => 'form-control',
                                'id' => trans('user.label_for.phone_number'),
                                'placeholder' => trans('user.placeholder.phone_number'),
                                'maxlength' => config('settings.length_user.phone_number'),
                            ])
                        }}
                    </div>
                </div>

                <!-- AVATAR -->
                {{ Form::label(trans('user.label_for.avatar'), trans('user.label.avatar')) }}
                <div class="form-group">
                    <div class="form-line">
                        {{
                            Form::file('avatar', [
                                'id' => trans('user.label_for.avatar'),
                                'onchange' => 'readURL(this, "preview-avatar")',
                                'maxlength' => config('settings.length_user.avatar'),
                            ])
                        }}
                    </div>
                    <img id="preview-avatar" src="#" class="preview-image avatar-new" />
                </div>

                <!-- PASSWORD -->
                {{ Form::label(trans('user.label_for.password'), trans('user.label.password') . trans('user.label.required')) }}
                <div class="form-group">
                    <div class="form-line">
                        {{
                            Form::password('password', [
                                'class' => 'form-control',
                                'id' => trans('user.label_for.password'),
                                'placeholder' => trans('user.placeholder.password'),
                                'required' => true,
                                'maxlength' => config('settings.length_user.password'),
                            ])
                        }}
                    </div>
                </div>

                <!-- PASSWORD CONFIRM-->
                {{ Form::label(trans('user.label_for.password_confirm'), trans('user.label.password') . trans('user.label.required')) }}
                <div class="form-group">
                    <div class="form-line">
                        {{
                            Form::password('password', [
                                'class' => 'form-control',
                                'name' => 'password_confirmation',
                                'placeholder' => trans('user.placeholder.password_confirm'),
                                'required' => true,
                            ])
                        }}
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="row clearfix">
                    {{
                        Form::submit(trans('user.button.create'), [
                            'class' => 'col-lg-3 col-lg-offset-2 btn btn-success waves-effect'
                        ])
                    }}
                    <a href="{{ route('admin.user.index') }}" class="col-lg-3 col-lg-offset-2 btn btn-primary waves-effect">
                        {{ trans('user.button.back') }}
                    </a>
                </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
