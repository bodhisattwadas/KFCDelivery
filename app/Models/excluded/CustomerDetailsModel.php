<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDetailsModel extends Model
{
    use HasFactory;
    protected $fillable = ['name','contact_number','address_line_1','address_line_2','city',
                            'latitude','longitude','pin'];
}
