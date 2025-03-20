<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

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
    // public function last_name()
    // {
    //     return Attribute::make(
    //         get: fn($value) => ucfirst($value ?? ''),
    //         set: fn($value) => ucfirst($value),
    //     );
    // }
    // protected function fullName()
    // {
    //     return Attribute::make(
    //         get: fn() => $this->first_name . " " . $this->last_name
    //     );
    // }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->ins_id = auth()->user()->id;
            $model->del_flag = 0;
        });

        static::updating(function ($model) {
            $model->upd_id = auth()->user()->id;
        });
    }
    protected static function booted()
    {
        static::addGlobalScope('active', function ($query) {
            $query->where('del_flag', 0);
        });
    }

    public function scopeSearchName($query, $keyword)
    {
        return $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$keyword}%"]);
    }



    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function getAuthIdentifierName()
    {
        return 'email'; // Hoặc đổi thành 'username' nếu login bằng username
    }

    public function delete()
    {
        $this->del_flag = 1; // Đánh dấu là bị xóa
        return $this->save();
    }

    // Thêm phương thức restore() để khôi phục bản ghi
    public function restore()
    {
        $this->del_flag = 0; // Bỏ đánh dấu xóa
        return $this->save();
    }

    // Tạo scope để lọc ra các bản ghi chưa bị xóa
    // public function scopeActive($query)
    // {
    //     return $query->where('del_flag', 0);
    // }

    // Kiểm tra xem bản ghi có bị xóa hay không
    public function trashed()
    {
        return $this->del_flag == 1;
    }
}
