<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;

class FolderController extends Controller
{
    public function showCreateForm() {
        return view('folders/formcreate');
    }

    public function create(Request $request) {
        $folder = new Folder();
        $folder->title = $request->title;
        $folder->save();

        return redirect()->route('tasks.index', [
            'id' => $folder->id,
        ]);
    }

    public function rules() {
        return [
            'title' => 'required|max:20',
        ];
    }

}


