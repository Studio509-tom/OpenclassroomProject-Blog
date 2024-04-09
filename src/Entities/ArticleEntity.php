<?php

namespace Application\Entities;

/**
 * ArticleEntity
 */
class ArticleEntity
{
    public string $id;
    public string $title;
    public string $chapo;
    public string $content;
    public $author;
    public string $date;

    public function hasAuthor(){
        if($this->author !== null){
            return true;
        }else{
            return false;
        }
    }

    // public function titleEmpty(){
    //     var_dump($this->title);
    //     if($this->title == ""){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // }

    // public function chapoEmpty(){
    //     if($this->chapo == ""){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // }

    // public function contentEmpty(){
    //     if($this->content == ""){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // }
}
