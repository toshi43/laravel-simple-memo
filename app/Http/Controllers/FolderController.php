<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Memo;
use App\Http\Requests\CreateFolder;


class FolderController extends Controller
{
    public function showCreateForm() {
        return view('folders/formcreate');
    }

    public function create(CreateFolder $request)
    {

        $folder = new Folder();
        $folder->title = $request->title;
        $folder->user_id=\Auth::id();
        $folder->save();


        
        return redirect()->route('index' , [
            'id' => $folder->id
        ]);
    }
}


