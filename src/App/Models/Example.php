<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FilterableTrait;
use Kyslik\ColumnSortable\Sortable;

class Example extends Model
{
    use SoftDeletes, FilterableTrait, Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'descriptions',
        'price',
        'image'
    ];

    /**
     * The attributes that should be cast to specific data types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'image' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
