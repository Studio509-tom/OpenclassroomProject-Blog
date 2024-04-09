<?php

namespace Application\Entities;

class CommentEntity
{
    public string $id;
    public string $content_comment;
    public $user;
    public $validate;
    public $article;
    
    /**
     * isValidate
     *
     * @return bool
     */
    public function isValidate():bool
    {
        if ($this->validate) {
            return true;
        } else {
            return false;
        }
    }
}
