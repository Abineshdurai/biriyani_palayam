<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class admin_model extends Model
{
    use Notifiable;
    public $timestamps = false;
    protected $table = 'admin'; // specify the table name
    protected $fillable = [
     'admin_id',
     'name',
     'mobile',
     'email',
     'address',
     'token',
     'admin_type',
     'category',
     'joining_date',
     'status',
     'created_at',
     'updated_at'
    ];
          
    // protected $hidden = [
    //     'password', 'remember_token',
    // ];
}
