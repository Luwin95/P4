<?php

namespace WriterBlog\DAO;

use WriterBlog\Domain\Chapter;

class ChapterDAO extends DAO
{
    /**
     * Returns a chapter matching the supplied id.
     *
     * @param integer $id
     *
     * @return \WriterBlog\Domain\Chapter|throws an exception if no matching article is found
     */
    public function find($id) {
        $sql = "select * from t_chapter where chapter_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
        {
            return $this->buildDomainObject($row);
        }else{
            throw new \Exception("No chapter matching id " . $id);
        }
    }

    /**
     * Returns a list of all chapters, sorted by id.
     *
     * @return array A list of all chapters.
     */
    public function findAll() {
        $sql = "select * from t_chapter order by chapter_id";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['chapter_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * Returns a list of all chapters published, sorted by id.
     *
     * @return array A list of all chapters published.
     */
    public function findAllPublished() {
        $sql = "select * from t_chapter where chapter_publishment=true order by chapter_id Desc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['chapter_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }
    
    /**
     * Saves a chapter into the database.
     *
     * @param \WriterBlog\Domain\Chapter $chapter The chapter to save
     */
    public function save(Chapter $chapter) {
        
        if(!$chapter->getPublishment())
        {
            $publishment = 0;
        }else{
            $publishment = 1;
        }

        $commentData = array(
            'chapter_content' => $chapter->getContent(),
            'chapter_title' => $chapter->getTitle(),
            'chapter_publishment' => $publishment
            );
        
        if ($chapter->getId()) {
            // The chapter has already been saved : update it
            $this->getDb()->update('t_chapter', $commentData, array('chapter_id' => $chapter->getId()));
        } else {
            // The chapter has never been saved : insert it
            $this->getDb()->insert('t_chapter', $commentData);
            // Get the id of the newly created chapter and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $chapter->setId($id);
        }
    }

    /**
     * Removes a chapter from the database.
     *
     * @param integer $id The chapter id.
     */
    public function delete($id) {
        // Delete the chapter
        $this->getDb()->delete('t_chapter', array('chapter_id' => $id));
    }

    /**
     * Creates a User object based on a DB row.
     *
     * @param array $row The DB row containing User data.
     * @return \WriterBlog\Domain\Chapter
     */
    protected function buildDomainObject(array $row) {
        $chapter = new Chapter();
        $chapter->setId($row['chapter_id']);
        $chapter->setTitle($row['chapter_title']);
        $chapter->setContent($row['chapter_content']);
        $chapter->setPublishment($row['chapter_publishment']);
        return $chapter;
    }
}
