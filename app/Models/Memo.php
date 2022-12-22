<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Memo extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'memos';
    protected $fillable = ['content'];
    

    // 1対多の逆所属で取得
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    // 多対多の関係で紐づくTagsをすべて取得
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'memo_tags');
    }

    public function getMymemo(){
        $query_tag = \Request::query('tag');
        $query_folder = \Request::query('folder');

        // ===　ベースのメソッド === //
        $query = Memo::query()->select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC');
        // ===　ベースのメソッドここまで === //
        
        //もしクエリパラメータtagがあれば
        if( !empty($query_tag) ){
            $query  ->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
                    ->where('memo_tags.tag_id', '=', $query_tag);
        }

        if( !empty($query_folder) ){
            $query  ->leftJoin('memos', 'memos.folder_id', '=', 'folders.id')
                    ->where('folders.folder_id', '=', $query_folder);
        }
        

        $memos = $query->get();

        return $memos;
    
    }

}