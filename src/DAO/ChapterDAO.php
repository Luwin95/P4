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
        $sql = "select * from t_chapter where chapter_publishment=true order by chapter_id";
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
