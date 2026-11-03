<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemilikHomestay extends Model
{
    use HasFactory;

    protected $table = 'pemilik_homestay';

    protected $primaryKey = 'id_pemilik';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama_pemilik',
        'no_hp',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function homestay()
    {
        return $this->hasMany(Homestay::class, 'id_pemilik', 'id_pemilik');
    }
}
