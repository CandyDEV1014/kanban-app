<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'order'];

    public $timestamps = false;

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('order');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (Column $column) {
            Task::where('column_id', $column->id)->delete();
        });
    }
}
