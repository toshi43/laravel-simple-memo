<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\Folder;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //すべてのメソッドが呼ばれる前に先に呼ばれるメソッド
        view()->composer('*', function ($view) {
            //自分のメモ取得はMemoモデルに任せる
            //インスタンス化
            $memo_model = new Memo();
            //メモ取得


            $request = request();

            $folder_id = $request->input('folder_id');
            // フォルダIDの有無で絞り込み
            if($folder_id){
                $memos = Folder::find($folder_id)->memos();
            } else {
                $memos = $memo_model->where('user_id', '=', \Auth::id())->get();
                $memos = Memo::where('user_id', '=', \Auth::id())
                ->whereNull('deleted_at')
                ->orderBy('id', 'DESC');
            }
            // 検索ワードで絞込
            $word = $request->search;
            if($word){
                $memos = $memos->where('content', 'like', '%'.$word.'%')->get();
            } else {
                $memos = $memos->get();
            }

            
            $current_folder = Folder::find($folder_id);
            // $memos = Memo::where('folder_id', $current_folder->id)->get();

            $tags = Tag::where('user_id', '=', \Auth::id())
                ->whereNull('deleted_at')
                ->orderBy('id', 'DESC')
                ->get();

            

            $view ->with('memos', $memos)->with('tags', $tags);
        });
        

    }
}