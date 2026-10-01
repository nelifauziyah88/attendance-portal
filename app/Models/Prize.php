<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    protected $fillable = ['name', 'stock', 'image'];

    public function luckySpins()
    {
        return $this->hasMany(LuckySpin::class);
    }
}