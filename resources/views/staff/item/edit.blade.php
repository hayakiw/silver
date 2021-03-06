@extends('staff.layout.master')

<?php
    $layout = [
        'title' => 'サービス編集',
        // 'description' => '○○のページです。',
        'js' => [],
    ];
?>

@section('content')
<div class="container">
  <div class="page-header">
    <h1>サービス編集</h1>
  </div>

  {!! Form::model($item, ['route' => ['staff.item.update', $item], 'method' => 'put', 'files' => true, 'class' => 'form-horizontal']) !!}
    @include('staff.item._form', ['item' => $item])
    <div class="form-group">
      <div class="col-md-offset-2 col-md-4">
        <input type="submit" name="submit" value="更新" class="btn btn-success btn-block">
      </div>
    </div>
  {!! Form::close() !!}
</div>

@endsection
