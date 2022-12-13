<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    public function getMyfolder(){
        $query_folder = \Request::query('folder');

        $query = Folder::query()->select('folders.*')
                ->where('user_id', '=', \Auth::id())
                ->whereNull('deleted_at')
                ->oder_by('updated_at' , 'DESK');

        if( !empty($query_folder) ){
            $query  ->leftJoin('meomo_folders', 'memo_folders.memo_id', '=', 'folders.id')
                    ->where('memo_folders.folder_id', '=', $query_folder);
        }
        
        $folders = $query->get();
        
        return $folders;
    }
    
}
