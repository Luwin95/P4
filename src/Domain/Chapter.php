<?php

namespace WriterBlog\Domain;

class Chapter
{
    /**
     * Chapter id.
     *
     * @var integer
     */
    private $id;

    /**
     * Chapter title.
     *
     * @var string
     */
    private $title;

    /**
     * Chapter content.
     *
     * @var string
     */
    private $content;

    /**
     * Chapter publishment state.
     *
     * @var boolean
     */
    private $publishment;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function getPublishment() {
        return $this->publishment;
    }

    public function setPublishment($publishment) {
        $this->publishment = $publishment;
        return $this;
    }
}