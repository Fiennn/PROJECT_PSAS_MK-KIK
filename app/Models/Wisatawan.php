<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisatawan extends Model
{
    use HasFactory;

    protected $table = 'wisatawan';

    protected $primaryKey = 'id_wisatawan';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama_wisatawan',
        'no_hp',
        'asal_kota',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function booking()
    {
        return $this->hasMany(Booking::class, 'id_wisatawan', 'id_wisatawan');
    }
}
