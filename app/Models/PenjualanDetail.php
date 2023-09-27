<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_detail';
    protected $primaryKey = 'id_penjualan_detail';
    protected $guarded = [];
    protected $fillable = [];
    
    public function produk()
    {
        return $this->hasOne(Produk::class, 'id_produk', 'id_produk');
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'id_member', 'id_member');
    }

    public function terapis()
    {
        return $this->hasOne(Terapis::class, 'id_terapis', 'id_terapis');
    }
}
