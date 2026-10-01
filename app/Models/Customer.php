<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'document_id',
    ];

    protected $table = 'customers';

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
