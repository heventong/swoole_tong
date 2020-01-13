<?php
namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model{
    protected $table = 'users';
    //指定主键
    protected $primaryKey = 'user_id';

}