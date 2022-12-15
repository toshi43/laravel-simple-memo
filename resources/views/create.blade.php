@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">新規メモ作成</div>
    <form class="card-body my-card-body" action="{{ route('store') }}" method="POST">
        @csrf
        <!-- プルダウン -->
        <div class="form-group">
            <select class="form-control mb-3" name="selectfolder_id" placeholder="">
                @foreach ($folders as $folder)
                    <option hidden>フォルダを選択</option>
                    <option value="{{ $folder->id }}">{{ $folder->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <textarea class="form-control mb-3" name="content" rows="3" placeholder="ここにメモを入力"></textarea>
        </div>
    @error('content')
        <div class="alert alert-danger">メモ内容を入力してください！</div>
    @enderror
   
        <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新しいタグを入力"/>
        <button type="submit" class="btn btn-primary">保存</button>
    </form>
</div>
@endsection
