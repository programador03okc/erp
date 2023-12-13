<?php

namespace App\Models\Almacen;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CdpRequerimiento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'almacen.cdp_requerimiento';
    protected $primaryKey = 'id_cdp_requerimiento';

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

 }
