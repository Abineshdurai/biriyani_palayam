<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class banner_model extends Model 
{
    use HasFactory;
    public $timestamp = false;
    protected $table = 'banner';
    protected $fillable = ['banner_id', 'banner_image','status', 'created_at'];



}