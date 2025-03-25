<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = "m_employees";
    protected $fillable = [
        'team_id',
        'email',
        'first_name',
        'last_name',
        'password',
        'gender',
        'birthday',
        'address',
        'avatar',
        'salary',
        'position',
        'status',
        'type_of_work',
        'del_flag'
    ];

    public function setPasswordAttribute($value)
    {
        if (!password_get_info($value)['algo']) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

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

    protected static function booted()
    {
        static::addGlobalScope('active', function ($query) {
            $query->where('del_flag', IS_NOT_DELETED);
        });
    }

    public function scopeSearchName($query, $keyword)
    {
        return $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$keyword}%"]);
    }

    public function team()//relationship
    {
        return $this->belongsTo(Team::class);
    }

    public function getAuthIdentifierName()
    {
        return 'email'; // authenticate field
    }

    //Update del_flag to 1, so that upd_datetime and upd_id are automatically updated
    public function delete()
    {
        $this->del_flag = IS_DELETED; // Update del_flag to 1
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
}
