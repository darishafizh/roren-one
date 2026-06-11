<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyediaJasaKonstruksi extends Model
{
    protected $connection = 'mysql_knmp';
    protected $table = 'penyedia_jasa_konstruksi';
    protected $fillable = ['nama'];
}
