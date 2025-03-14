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
    protected $guarded = []; // Loại bỏ bảo vệ trường nào đó

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
    //global scope
    protected static function booted()
    {
        static::addGlobalScope('active', function ($query) {
            $query->where('del_flag', 0);
        });
    }
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function delete()
    {
        $this->del_flag = 1; // Đánh dấu là bị xóa
        $employees = Employee::where('team_id', $this->id)->get();
        foreach ($employees as $employee) {
            $employee->delete();
        }
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

    public static function getFieldById($id, $field)
    {
        return self::where('id', $id)->value($field);
    }
}
