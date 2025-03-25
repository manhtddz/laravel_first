<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "m_teams";
    protected $fillable = [
        'name',
        'del_flag'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->ins_id = auth()->user()->id;
            $model->del_flag = IS_NOT_DELETED;
        });

        static::updating(function ($model) {
            $model->upd_id = auth()->user()->id;
        });
    }

    //global scope
    protected static function booted()
    {
        static::addGlobalScope('active', function ($query) {
            $query->where('del_flag', IS_NOT_DELETED);
        });
    }
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    
    //Update del_flag to 1, so that upd_datetime and upd_id are automatically updated
    public function delete()
    {
        $this->del_flag = IS_DELETED; // Update del_flag to 1
        $employees = Employee::where('team_id', $this->id)->get();
        foreach ($employees as $employee) {
            $employee->delete();
        }
        return $this->save();
    }

    // Recover deleted record
    public function restore()
    {
        $this->del_flag = IS_NOT_DELETED; // Recover del_flag to 0
        return $this->save();
    }

    // Check is deleted
    public function trashed()
    {
        return $this->del_flag == IS_DELETED;
    }

    public static function getFieldById($id, $field)
    {
        return self::where('id', $id)->value($field);
    }
}
