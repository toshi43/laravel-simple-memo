<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;
use App\Models\Folder;

use DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        $tags = Tag::where('user_id', '=', \Auth::id())->whereNull('deleted_at')->orderBy('id', 'DESC')
        ->get();

        //$folders = Folder::all();
        
        $folders = Folder::where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();
        
        $folder_id = $request->input('folder_id');
        // $memos = Folder::find($folder_id)->memos;

        //Log::info($folder_id);
        //$current_folder = Folder::find($folder_id);
        //選ばれたフォルダに紐づくタスクを取得する
        //$memos = Memo::where('folder_id', $current_folder->id)->get();
        

        //folder_idがNullの場合

        // if($current_folder === null) {
        //     return redirect( route('home') );
        // }



        return view('create', compact('tags' , 'folders'

        
        ));
    }

    public function store(Request $request)
    {
        $posts = $request->all();
        $request->validate( [ 'content' => 'required']);
        //ここからトランザクション開始
        DB::transaction(function() use($posts) {
        //　メモIDをインサートして取得
            $memo_id = Memo::insertGetId(['content' => $posts['content'], 'user_id' => \Auth::id(), 'folder_id' => $posts['selectfolder_id']]);
            $folder_exists = Memo::where('user_id' ,'=' , \Auth::id())
            ->exists();
            $tag_exists = Tag::where('user_id' ,'=' , \Auth::id())->where('name', '=', $posts['new_tag'])
            ->exists();
            
           // Memo::insert(['folder_id' => $folder_id]);
        //新規タグが入力されているかチェック
        //新規タグが既にtagsテーブルに存在するかのチェック
            if( !empty($posts['new_tag']) && !$tag_exists ){
                //新規タグが既に存在しなければ、tagsテーブルにインサート→IDを取得
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                //meo_tagsにインサートして、メモとタグを紐付ける
                MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
            }
            //既存タグが紐づけられた場合→memo_tagsにインサート
            if(!empty($posts['tags'][0])){
                foreach($posts['tags'] as $tag){
                    MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag]);
                }
            }
        });

        // return view('create', compact('folders'
        // [
        //      $folder = Folder::find($folder_id)
        //  ]));
       
        return redirect( route('home') );
    }

    public function edit($id)
    {
        $edit_memo = Memo::find($id);
        

        // $edit_memo = Memo::select('memos.*', 'tags.id AS tag_id')
        //     ->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
        //     ->leftJoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
        //     ->where('memos.user_id', '=', \Auth::id())
        //     ->where('memos.id', '=', $id)
        //     ->whereNull('memos.deleted_at')
        //     ->get();

        //リレーションで取得
        $tags = $edit_memo->tags;
        $include_folder = $edit_memo->folder;


        $include_tags = [];
        // リレーションで紐づいたタグのIDを取得
        foreach($tags as $tag){
            array_push($include_tags, $tag['id']);
        }
       


        $tags = Tag::where('user_id', '=', \Auth::id())->whereNull('deleted_at')->orderBy('id', 'DESC')
        ->get();
        $folders = Folder::where('user_id', '=', \Auth::id())->whereNull('deleted_at')->orderBy('id', 'DESC')
        ->get();
        return view('edit', compact('edit_memo', 'include_tags', 'tags', 'folders', 'include_folder'));
    }

    public function update(Request $request)
    {
        $posts = $request->all();
        $request->validate( [ 'content' => 'required']);
        //トランザクションスタート
        DB::transaction(function() use($posts){
            Memo::where('id', $posts['memo_id'])->update(['content' => $posts['content'], 'folder_id' => $posts['selectfolder_id']]);
            //一旦メモとタグの紐付けを解除
            MemoTag::where('memo_id', '=', $posts['memo_id'])->delete();
            //再度メモとタグの紐付け
            foreach ($posts['tags'] as $tag) {
                MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag]);
            }
             //新規タグが入力されているかチェック
            $tag_exists = Tag::where('user_id' ,'=' , \Auth::id())->where('name', '=', $posts['new_tag'])
            ->exists();
            //新規タグが既にtagsテーブルに存在するかのチェック
            if( !empty($posts['new_tag']) && !$tag_exists ){
                //新規タグが既に存在しなければ、tagsテーブルにインサート→IDを取得
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                //meo_tagsにインサートして、メモとタグを紐付ける
                MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag_id]);
            }
            //もし、新しいタグの入力があれば、インサートして紐付ける
            
        });
        
        

        return redirect( route('home') );
    }

    public function destroy(Request $request)
    {
        $posts = $request->all();
        
        //Memo::where('id', $posts['memo_id'])->deleate();←NGこれをやると物理削除
        Memo::where('id', $posts['memo_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);
        
        return redirect( route('home') );
    }

    public function folderedit($id)
    {
        $folder = Folder::find($id);
        $folders = Folder::where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();
        
        $edit_folder = Folder::find($id);
        $edit_memo = Memo::find($id);
        
        return view('folders/folderedit', compact('folder', 'folders', 'edit_folder' , 'edit_memo'));
    }

}