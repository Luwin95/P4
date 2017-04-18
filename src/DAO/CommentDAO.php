<?php

namespace WriterBlog\DAO;

use WriterBlog\Domain\Comment;

class CommentDAO extends DAO
{
    private $chapterDAO;
    private $userDAO;
    private $commentDAO;
    
    public function setChapterDAO(ChapterDAO $chapterDAO)
    {
        $this->chapterDAO = $chapterDAO;
    }
    
    public function setUserDAO(UserDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }
    
    public function setCommentDAO(CommentDAO $commentDAO)
    {
        $this->commentDAO = $commentDAO;
    }
    
    /**
     * Returns a comment matching the supplied id.
     *
     * @param integer $id
     *
     * @return \WriterBlog\Domain\Comment|throws an exception if no matching article is found
     */
    public function find($id) {
        $sql = "select * from t_comment where comment_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
        {
            return $this->buildDomainObject($row);
        }else{
            throw new \Exception("No comment matching id " . $id);
        }
    }
    
    /**
     * Returns all comments matching a chapter id.
     *
     * @param integer $chapterId
     *
     * @return \WriterBlog\Domain\Comment|throws an exception if no matching article is found
     */
    public function findAllByChapter($chapterId) {
        $chapter = $this->chapterDAO->find($chapterId);
        
        $sql = "select comment_id, comment_date, comment_content, user_id, parent_id from t_comment where chapter_id=?";
        $result = $this->getDb()->fetchAll($sql, array($chapterId));
        
        $comments = array();
        
        foreach($result as $row) {
            $comment = $this->buildDomainObject($row);
            $comment->setChapter($chapter);
            $comments[$row['comment_id']] = $comment;
        }
        return $comments;
        
    }

    

    /**
     * Creates a comment object based on a DB row.
     *
     * @param array $row The DB row containing User data.
     * @return \WriterBlog\Domain\Comment
     */
    protected function buildDomainObject(array $row) {
        $comment = new Comment();
        $comment->setId($row['comment_id']);
        $comment->setDate($row['comment_date']);
        $comment->setContent($row['comment_content']);
        
        if(array_key_exists('chapter_id', $row)) {
            $chapter = $this->chapterDAO->find($row['chapter_id']);
            $comment->setChapter($chapter);
        }
        
        if(array_key_exists('user_id', $row)) {
            $author = $this->userDAO->find($row['user_id']);
            $comment->setAuthor($author);
        }
        
        if(array_key_exists('parent_id', $row)) {
            if($row['parent_id']!=0)
            {
                $parent = $this->find($row['parent_id']);
                $comment->setParent($parent);
            } 
        }
        
        return $comment;
    }
}
