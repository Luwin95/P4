<?php

namespace WriterBlog\Domain;

class Comment
{
    /**
     * Comment id.
     *
     * @var integer
     */
    private $id;

    /**
     * Comment date.
     *
     * @var datetime
     */
    private $date;

    /**
     * Comment content.
     *
     * @var string
     */
    private $content;
    
    /**
     * Comment related chapter.
     *
     * @var \WriterBlog\Domain\Chapter
     */
    private $chapter;
    
    /**
     * Comment author.
     *
     * @var \WriterBlog\Domain\User
     */
    private $author;

    /**
     * Comment parent if exist (else null).
     *
     * @var \WriterBlog\Domain\Comment
     */
    private $parent;
    
    /**
     * Comment children.
     *
     * @var \WriterBlog\Domain\Comment
     */
    private $children;

    /**
     * Comment reported status.
     *
     * @var \WriterBlog\Domain\User
     */
    private $reported;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function getChapter() {
        return $this->chapter;
    }

    public function setChapter(Chapter $chapter) {
        $this->chapter = $chapter;
        return $this;
    }
    
    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor(User $author) {
        $this->author = $author;
        return $this;
    }
    
    public function getParent() {
        return $this->parent;
    }
    
    public function setParent(Comment $parent) {
        $this->parent = $parent;
    }
    
    public function getChildren() {
        return $this->children;
    }
    
    public function addChildren(Comment $children) {
        $this->children[] = $children;
    }

    public function getReported() {
        return $this->reported;
    }

    public function setReported($reported) {
        $this->reported = $reported;
    }
   
}