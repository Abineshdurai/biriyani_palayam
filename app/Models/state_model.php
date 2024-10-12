<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class state_model extends Model{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'states';
    protected $fillable = [
        "state_id",
        "state",
        "status ",
        "created_at",
        "updated_at"
    ];
}
