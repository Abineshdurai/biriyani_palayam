<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class franchise_model extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'franchise';
    protected $fillable = [
        'state_id',
        'district_id',
        'franchise', 
        'franchise_id', 
        'description', 
        'address', 
        'owner_name', 
        'mobile_no', 
        'franchise_image', 
        'status', 
        'created_at'];
}
