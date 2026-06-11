<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonstruksiKnmp extends Model
{
    protected $connection = 'mysql_knmp';
    protected $table = 'konstruksi_knmp';
    protected $fillable = ['knmp_id', 'jasa_konstruksi_id', 'tanggal_mulai'];

    public function knmp()
    {
        return $this->belongsTo(Knmp::class, 'knmp_id');
    }

    public function penyediaJasa()
    {
        return $this->belongsTo(PenyediaJasaKonstruksi::class, 'jasa_konstruksi_id');
    }

    public function tahapKonstruksi()
    {
        return $this->hasMany(TahapKonstruksi::class, 'knmp_konstruksi_id');
    }
}
