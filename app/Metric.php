<?php

namespace App;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    const SAVED = 'saved';
    const APPROVED = 'approved';
    const DISAPPROVED = 'disapproved';
    const SYNCED = 'synced';
    use HasFactory, HasRelationships;
    protected $table = 'metrics' ;
    protected $fillable =[
        'user_id',
        'metricCode',
        'metricType',
        'value',
        'comment',
        'entryDate',
        'status',
        'metricEntryType',
        'item_status',
        'month',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
