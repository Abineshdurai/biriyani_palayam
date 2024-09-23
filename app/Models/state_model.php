<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class state_model extends Model{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'states';
    protected $fillable = [
        "name",
        "country_id ",
        "country_code",
        "state_id",
        "state_code ",
        "created_at",
        "updated_at"
    ];
}