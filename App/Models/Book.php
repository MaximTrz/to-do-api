<?php


namespace App\Models;

use System\Models\Model;

class Book extends Model
{
    const TABLE = 'books';
    public $title;
    public $year;
    public $author;

}