<?php

namespace App;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    use HasFactory, HasRelationships;
    protected $table = 'kpi' ;
    protected $fillable =[
        'code',
        'type',
        'value',
        'comment',
        'entryDate',
        'status',
        'entry_type'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
