<?php


namespace App\Models;


use System\Models\Model;

class Task extends Model
{
    const TABLE = 'todo_list';
    public $text;
    public $user_name;
    public $status;
}