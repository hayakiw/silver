@extends('staff.layout.master')

<?php

    $layout = [
        'title' => 'メールアドレス変更',
    ];

?>

@section('content')

<div class="container">
  <div class="page-header">
    <h1>メールアドレス変更</h1>
  </div>


  {!! Form::model($user, ['route' => 'staff.user.request_email', 'method' => 'put', 'class' => 'form-horizontal']) !!}
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('new_email') ? ' has-error' : '' }}">
      <label for="new_email" class="control-label col-md-3">新しいメールアドレス</label>
      <div class="col-md-4">
        <input type="new_email" name="new_email" id="new_email" value="{{ old('new_email') }}" class="form-control {{ $errors->has('new_email') ? 'is-invalid' : '' }}" placeholder="半角英数字で入力">
        @if ($errors->has('new_email'))
          <span class="text-danger"><strong>{{ $errors->first('new_email') }}</strong></span>
        @endif
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-offset-2 col-md-10">
        <button type="submit" class="btn btn-success btn-block">変更する</button>
        <a href="{{ route('staff.user.show') }}" class="btn btn-secondary">戻る</a>
      </div>
    </div>

  {!! Form::close() !!}
</div>

@endsection
