<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesAddress extends Model
{
    use HasFactory;
    protected $fillable = ['sales_product_id', 'cep', 'state', 'city', 'district', 'street', 'number', 'complement'];
}
