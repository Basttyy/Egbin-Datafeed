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
        'code',
        'type',
        'value',
        'comment',
        'description',
        'entry_date',
        'status',
        'entry_type',
        'item_status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
