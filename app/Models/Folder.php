<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;   

    // 1対多の関係で紐づくメモをすべて取得
    public function memos()
    {
        return $this->hasMany(Memo::class);
    }
}
