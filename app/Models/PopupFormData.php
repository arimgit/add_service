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
        'user_id',
        'popup_id',
        'website_name',
        'name',
        'email',
        'mobile',
    ];
}
