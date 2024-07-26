<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopupFormData extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'popup_form_data';

    // The attributes that are mass assignable.
    protected $fillable = [
        'popup_id',
        'host_name',
        'form_data'
    ];

    protected $casts = [
        'form_data' => 'array', // Automatically cast JSON to array
    ];
}
