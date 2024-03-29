<?php

namespace App;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kpi extends Model
{
    use HasFactory, HasRelationships;
    protected $table = 'kpi' ;
    protected $fillable =[
        'kpi_name',
        'kpi_code',
        'kpi_description',
        'kpi_category',
        'email'];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}
