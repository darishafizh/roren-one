<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahapUsulan extends Model
{
    protected $connection = 'mysql_knmp';
    protected $table = 'tahap_usulan';
    protected $fillable = ['knmp_id', 'tanggal', 'catatan'];

    public function knmp()
    {
        return $this->belongsTo(Knmp::class, 'knmp_id');
    }
}
