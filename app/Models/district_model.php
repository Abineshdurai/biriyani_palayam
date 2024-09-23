<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class district_model extends Model{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'districts';
    protected $fillable = [
        "district_name",
        "district_id ",
        "country_id",
        "country_code",
        "state_id",
        "state_code ",
        "status",
        "created_at",
      //  "updated_at"
    ];
}