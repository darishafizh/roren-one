<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahapLelang extends Model
{
    protected $connection = 'mysql_knmp';
    protected $table = 'tahap_lelang';
    protected $fillable = ['knmp_id', 'tanggal_penetapan', 'catatan'];

    public function knmp()
    {
        return $this->belongsTo(Knmp::class, 'knmp_id');
    }
}
