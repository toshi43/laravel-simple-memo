<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Memo;
use App\Http\Requests\CreateFolder;
use App\Http\Requests\EditFolder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class FolderController extends Controller
{
    use HasFactory,SoftDeletes;

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

    
    public function update(Request $request, $id)
    {
        
        $folder = Folder::find($id);
        $folder->title = $request->title;
        $folder->user_id=\Auth::id();
        $folder->save();

        return redirect( route('home') );
    }

    
    public function destroy(Request $request)
    {
       $posts = $request->all();
       
        Folder::where('id', $posts['folder_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);
       
        return redirect( route('home') );
    }


    // public function folderedit($id)
    //  {
    //     $folder = Folder::find($id);
    
    //     return view('folderedit');
    // }
}


