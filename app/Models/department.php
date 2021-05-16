<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'position',
    ];

    public function scopeStaffs($query)
    {
        return $query->join('staffs', 'staffs.department_id', '=', 'departments.id')
                    ->select('staffs.*')
                    ->where('departments.id', $this->id);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }
}
