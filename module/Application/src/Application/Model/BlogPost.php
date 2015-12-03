<?php

/* 
 * The blog model
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Model;
use \Application\Model\CommonModel;

class BlogPost extends CommonModel
{
    /**
     * Up votes count
     * @var int
     */
    protected $upVotes   = 0;
    
    /**
     * Down votes count
     * @var int
     */
    protected $downVotes = 0;
    
    /**
     * Holds the title of the blog post
     * @var string 
     */
    protected $title     = "";
    
    /**
     * Holds who the blog post was created by
     * @var string
     */
    protected $createdBy = "";
    
    /**
     * Holds the body of the blog post
     * @var string
     */
    protected $body      = "";
    
    /**
     * Holds the date of the post
     * @var string
     */
    protected $date;
    
    /**
     * Holds the tags
     * @var String
     */
    protected $tags      = "";
    
    /**
     * Set the title
     * @param  string $title
     * @return \Application\Model\BlogPost
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Get the title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set the created by
     * @param  string $createdBy
     * @return \Application\Model\BlogPost
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }
    
    /**
     * Get the created by
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
    
    /**
     * Set the body text
     * @param  string $body
     * @return \Application\Model\BlogPost
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }
    
    /**
     * Get the body
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
    
    /**
     * Set the date
     * @param  string $date
     * @return \Application\Model\BlogPost
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }
    
    /**
     * Get the date
     * @return string
     */
    public function getDate()
    {
        $date = new \DateTime($this->date);
        return $date->format('m/d/Y');
    }
    
    /**
     * Set the up votes count
     * @param  int $voteCount
     * @return \Application\Model\BlogComment
     */
    public function setUpVotes($voteCount)
    {
        $this->upVotes = $voteCount;
        return $this;
    }
    
    /**
     * Get the up votes
     * @return int
     */
    public function getUpVotes()
    {
        return (int)$this->upVotes;
    }
    
    /**
     * Set the down votes count
     * @param  int $voteCount
     * @return \Application\Model\BlogComment
     */
    public function setDownVotes($voteCount)
    {
        $this->downVotes = $voteCount;
        return $this;
    }
    
    /**
     * Get the down votes count
     * @return int
     */
    public function getDownVotes()
    {
        return (int)$this->downVotes;
    }
    
    /**
     * Set the tags
     * @param  string $str
     * @return \Application\Model\BlogPost
     */
    public function setTags($str)
    {
        $this->tags = $str;
        return $this;
    }
    
    /**
     * Get the tags
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }
}