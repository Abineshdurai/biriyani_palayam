<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class scrolling_text_model extends Model
{
    use HasFactory;
    protected $table = 'scrolling_text';

    protected $fillable = ['scrolling_text_id', 'scrolling_text', 'status', 'created_at', 'updated_at'];
}
