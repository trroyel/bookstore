<?php
// Book model: Handles book data and logic
class Book
{
    public $id;
    public $title;
    public $author;
    public $available;
    public $pages;
    public $isbn;


    public function __construct($id, $title, $author, $available, $pages, $isbn)
    {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->available = $available;
        $this->pages = $pages;
        $this->isbn = $isbn;
    }
}
