<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class district_model extends Model{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'districts';
    protected $fillable = [
        "district",
        "district_id ",
        "state_id",
        "status",
        "created_at",
        "updated_at"
    ];
}
