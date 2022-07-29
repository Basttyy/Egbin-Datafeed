<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kpi extends Model
{
    use HasFactory;
    protected $table = 'kpi' ;
    protected $fillable =[
        'kpi_name',
        'kpi_code',
        'kpi_description',
        'kpi_category',
        'email'];
    
}
