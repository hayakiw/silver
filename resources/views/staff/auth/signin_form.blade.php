@extends('staff.layout.master')

<?php

    $layout = [
        'title' => 'ログイン',
    ];

?>

@section('content')
<div class="container">
  <div class="page-header">
    <h1>ログイン</h1>
    <p class="lead">一般のアカウントではログインできません。<br>
    スタッフ用のアカウントをお持ちでない方は、新規登録を行いご利用ください。</p>
  </div>

  <form method="post" action="{{ route('staff.auth.signin') }}" class="form-horizontal">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
      <label for="email" class="control-label col-md-2">E-Mail</label>
      <div class="col-md-4">
        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="user@example.com">
        @if ($errors->has('email'))
        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
        @endif
      </div>
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
      <label for="password" class="control-label col-md-2">パスワード</label>
      <div class="col-md-4">
        <input type="password" name="password" id="password" value="{{ old('password') }}" class="form-control" placeholder="password">
        @if ($errors->has('password'))
        <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
        @endif
      </div>
    </div>

    <div class="form-group{{ $errors->has('remember') ? 'has-error' : '' }}">
      <div class="col-md-offset-2 col-md-4">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="remember"> ログイン状態を保持する
          </label>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-offset-2 col-md-4">
        <button type="submit" class="btn btn-info btn-block"><i class="fa fa-sign-in"></i> ログイン</button>
        <a href="{{ route('staff.user.create') }}" class="btn btn-warning btn-block">新規登録はこちら</a>
        <a href="{{ route('staff.reset_password.request_form') }}">パスワードをわすれた方はこちら</a><br>
      </div>
    </div>
  </form>
</div>

@endsection
