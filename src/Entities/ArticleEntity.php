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
    
    /**
     * hasAuthor
     *
     * @return bool
     */
    public function hasAuthor():bool
    {
        if($this->author !== null){
            return true;
        }else{
            return false;
        }
    }

}
