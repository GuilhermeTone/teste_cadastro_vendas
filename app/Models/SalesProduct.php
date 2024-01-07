<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesProduct extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'amount', 'price_at_sale', 'date_sale'];
}
