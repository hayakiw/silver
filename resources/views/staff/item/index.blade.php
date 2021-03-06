@extends('staff.layout.master')

<?php
    $layout = [
        'title' => 'サービス管理',
        // 'description' => '○○のページです。',
        'js' => [],
    ];
?>

@section('content')
<div class="container">
  <div class="page-header">
    <h1>サービス管理</h1>
  </div>

<a href="{{ route('staff.item.create') }}" class="btn btn-default">新規登録</a>

@if($items->count())
<table class="table">
  <thead>
    <tr>
      <th>サービス名</th>
      <th>場所の詳細</th>
      <th>時給</th>
      <th>購入可能最大時間</th>
      <th>編集/削除</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($items as $key => $item)
    <tr>
      <td>{{ $item->title }}</td>
      <td>{{ $item->location }}</td>
      <td>{{ $item->price }}</td>
      <td>{{ $item->max_hours }}</td>
      <td>
        <a href="{{ route('staff.item.edit', $item) }}" class="btn btn-xs btn-warning">編集</a>
        /
        {!! Form::open(['route' => ['staff.item.destroy', $item], 'method' => 'delete', 'style' => 'display:inline;']) !!}
        <button name="delete" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure?');">削除</button>{!! Form::close() !!}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@else
<p>新規登録より、サービスを登録してください</p>
@endif

</div>
@endsection
