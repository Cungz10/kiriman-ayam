<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKiriman extends Model
{
    public $timestamps = false; // hanya created_at, sesuai skema asli

    protected $table = 'master_kiriman';

    protected $fillable = [
        'nama_kiriman',
    ];

    protected $attributes = [];

    protected static function booted(): void
    {
        static::creating(function (MasterKiriman $model) {
            $model->created_at = $model->created_at ?? now();
        });
    }

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
