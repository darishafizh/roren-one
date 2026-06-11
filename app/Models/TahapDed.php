<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahapDed extends Model
{
    protected $connection = 'mysql_knmp';
    protected $table = 'tahap_ded';
    protected $fillable = ['knmp_id', 'nomor_dokumen', 'tanggal_pengesahan', 'file_url', 'catatan'];

    public function knmp()
    {
        return $this->belongsTo(Knmp::class, 'knmp_id');
    }
}
