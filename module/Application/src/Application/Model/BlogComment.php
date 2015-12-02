<?php

/* 
 * The blog model
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Model;

class BlogComment extends \Application\Model\BlogPost
{
    /**
     * Holds the blog post id
     * @var int
     */
    protected $blogPostId = 0;
    
    /**
     * Set the blog post id
     * @param  int $id
     * @return \Application\Model\BlogComment
     */
    public function setBlogPostId($id)
    {
        $this->blogPostId = $id;
        return $this;
    }
    
    /**
     * Get the blog post id
     * @return type
     */
    public function getBlogPostId()
    {
        return $this->blogPostId;
    }
}