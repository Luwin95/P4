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
     * Returns all comments.
     *
     * @param integer $chapterId
     *
     * @return \WriterBlog\Domain\Comment|throws an exception if no matching article is found
     */
    public function findAll() {
        $sql = "select * from t_comment order by chapter_id";
        $result = $this->getDb()->fetchAll($sql);
        
        $comments = array();
        
        foreach($result as $row) {
            $id = $row['comment_id'];
            $comments[$id] = $this->buildDomainObject($row);
        }
        return $comments;
        
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

        $sql = "select comment_id, comment_date, comment_content, user_id, parent_id,comment_reported from t_comment where chapter_id=?";
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
     * Returns all comments matching a parent id.
     *
     * @param integer $parentId
     *
     * @return \WriterBlog\Domain\Comment|throws an exception if no matching article is found
     */
    public function findAllByParent($parentId) {
        $sql = "select comment_id, comment_date, comment_content, user_id, parent_id,comment_reported from t_comment where parent_id=?";
        $result = $this->getDb()->fetchAll($sql, array($parentId));

        $comments = array();

        foreach($result as $row) {
            $comment = $this->buildDomainObject($row);
            $comments[$row['comment_id']] = $comment;
        }
        return $comments;

    }

    /**
     * Returns all comments matching an author
     *
     * @param integer $userId
     *
     * @return \WriterBlog\Domain\Comment|throws an exception if no matching article is found
     */
    public function findAllByAuthor($userId) {
        $sql = "select comment_id, comment_date, comment_content, user_id, parent_id,comment_reported from t_comment where user_id=?";
        $result = $this->getDb()->fetchAll($sql, array($userId));

        $comments = array();

        foreach($result as $row) {
            $comment = $this->buildDomainObject($row);
            $comments[$row['comment_id']] = $comment;
        }
        return $comments;
    }
    
    /**
     * Saves a comment into the database.
     *
     * @param \WriterBlog\Domain\Comment $comment The comment to save
     */
    public function save(Comment $comment) {

        if($comment->getParent())
        {
            $parentId = $comment->getParent()->getId();
        }else{
            $parentId = 0;
        }

        if(!$comment->getReported())
        {
            $reported = 0;
        }else{
            $reported = 1;
        }

        $commentData = array(
            'comment_content' => $comment->getContent(),
            'comment_date' => $comment->getDate(),
            'chapter_id' => $comment->getChapter()->getId(),
            'user_id' => $comment->getAuthor()->getId(),
            'parent_id' => $parentId,
            'comment_reported' => $reported,
            );
        
        

        if ($comment->getId()) {
            // The comment has already been saved : update it
            $this->getDb()->update('t_comment', $commentData, array('comment_id' => $comment->getId()));
        } else {
            // The comment has never been saved : insert it
            $this->getDb()->insert('t_comment', $commentData);
            // Get the id of the newly created comment and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $comment->setId($id);
        }
    }

    /**
     * Removes a comment matching and all its children
     *
     * @param $commentId The id of the chapter
     */
    public function delete($id) {

        if($this->findAllByParent($id) != NULL)
        {
            $children = $this->findAllByParent($id);
            foreach($children as $child)
            {
                $this->delete($child->getId());
            }
        }
        $this->getDb()->delete('t_comment', array('comment_id' => $id));
    }

    /**
     * Removes all comments for a chapter
     *
     * @param $chapterId The id of the chapter
     */
    public function deleteAllByChapter($chapterId) {
        $this->getDb()->delete('t_comment', array('chapter_id' => $chapterId));
    }

    /**
     * Removes all comments written by a specific user (and there children)
     *
     * @param $userId The id of the chapter
     */
    public function deleteAllByUser($userId) {
        $commentsToDelete = $this->findAllByAuthor($userId);
        foreach($commentsToDelete as $commentToDelete)
        {
            $this->delete($commentToDelete->getId());
        }
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
        $comment->setReported($row['comment_reported']);
        
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
