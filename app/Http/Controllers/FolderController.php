<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Memo;


class FolderController extends Controller
{
    public function showCreateForm() {
        return view('folders/formcreate');
    }

    public function create(Request $request)
    {
        $posts = $request->all();
        $request->validate( [ 'content' => 'required']);

        //ここからトランザクション開始
        DB::transaction(function() use($posts) {
        //　メモIDをインサートして取得
            $memo_id = Memo::insertGetId(['content' => $posts['content'], 'user_id' => \Auth::id()]);
             $folder_exists = folder::where('user_id' ,'=' , \Auth::id())->where('name', '=', $posts['new_fokder'])
            ->exists();
            if( !empty($posts['new_folder']) && !$folder_exists ){
                $folder_id = Folder::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_folder']]);
                MemoFolder::insert(['memo_id' => $memo_id, 'folder_id' => $folder_id]);
            }
            if(!empty($posts['folders'][0])){
                foreach($posts['folders'] as $folder){
                    MemoFolder::insert(['memo_id' => $memo_id, 'folder_id' => $folder]);
                }
            }
        });
    }
    //public function showCreateForm() {
    //    return view('folders/formcreate');
    //}

    //public function create(Request $request) {
    //    $folder = new Folder();
    //    $folder->content = $request->content;
    //    $folder->save();

    //    return redirect( route('home') );
    //}

    public function rules() {
        return [
            'title' => 'required|max:20',
        ];
    }

}


