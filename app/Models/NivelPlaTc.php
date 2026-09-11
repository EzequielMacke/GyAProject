<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelPlaTc extends Model
{
    use HasFactory;

    protected $table = 'nivel_pla_tc';

    protected $fillable = [
        'obra_tc_id',
        'descripcion',
    ];

    public function obra()
    {
        return $this->belongsTo(ObraTc::class, 'obra_tc_id');
    }
}
