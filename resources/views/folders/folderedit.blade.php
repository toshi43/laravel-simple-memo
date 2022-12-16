@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        フォルダ編集
        <form id="delete-form" 
        @csrf
        {{-- <input type="hidden"  name="folder_id" value="{{ $edit_folder['id'] }}" /> --}}
        <i class="fas fa-trash mr-3" onclick="deleteHandle(event);"></i>
        </form>
    </div>
    {{-- <form class="card-body  my-card-body" action="{{ route('/folders/{{$folder['id']}}/update) }}" method="POST"> 
    @csrf
        <div class="form-group">
            <div class="form-group">
                <select class="form-control mb-3" name="selectfolder_id">
                    <option></option>
                    @foreach ($folders as $folder)
                        <option value="{{ $folder['id'] }}" @if($folder['id'] == $edit_memo['folder_id']) selected @endif>{{ $folder->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>--}}
</div>
@endsection
