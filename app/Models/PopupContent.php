<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopupContent extends Model
{
    use HasFactory;

    protected $table = 'table_popup_content';

    protected $fillable = [
        'user_id',
        'content',
        'type',
    ];
}
