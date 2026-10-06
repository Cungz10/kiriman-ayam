<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class RiwayatInput extends Model
{
    public $timestamps = false; // hanya created_at, sesuai skema asli

    protected $table = 'riwayat_input';

    protected $fillable = [
        'nama_kiriman',
        'nomer_po',
        'data_input',
        'total_data',
        'rata_rata',
        'nilai_max',
        'nilai_min',
    ];

    protected static function booted(): void
    {
        static::creating(function (RiwayatInput $model) {
            $model->created_at = $model->created_at ?? now();
        });
    }

    /**
     * Custom accessor/mutator untuk data_input.
     * Bisa handle format lama (CSV: "5.3,4.7,5.6") dan
     * format baru (JSON array: "[5.3,4.7,5.6]").
     */
    protected function dataInput(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_null($value)) return [];

                // Coba decode JSON dulu
                $decoded = json_decode($value, true);
                if (is_array($decoded)) {
                    return array_map('floatval', $decoded);
                }

                // Fallback: format CSV lama (tanpa bracket)
                return array_map('floatval', explode(',', $value));
            },
            set: function ($value) {
                // Kalau sudah array, encode ke JSON
                if (is_array($value)) {
                    return json_encode(array_values($value));
                }

                // Kalau string CSV tanpa bracket, bungkus jadi JSON array
                if (is_string($value) && !str_starts_with(trim($value), '[')) {
                    $nums = array_map('floatval', explode(',', $value));
                    return json_encode($nums);
                }

                return $value;
            },
        );
    }

    protected $casts = [
        'rata_rata' => 'float',
        'nilai_max' => 'float',
        'nilai_min' => 'float',
        'created_at' => 'datetime',
    ];
}

