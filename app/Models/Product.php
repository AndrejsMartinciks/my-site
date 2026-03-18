<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Разрешаем заполнять эти поля из формы
    protected $fillable = ['name', 'price'];
}
