<?php

namespace App\Model;

use PDO;

class ChapterManager extends AbstractManager
{
    public const TABLE = 'chapter';

    /**
     * Insert new chapter in database
     */
    public function adminInsert(array $chapter): int
    {
        $query = "INSERT INTO " . self::TABLE . " (
            `name`,
            `title`,
            `description`,
            `background_image`,
            `background_image_alt`
            )

             VALUES (
                :name,
                :title,
                :description,
                :background_image,
                :background_image_alt             
                )";

        $statement = $this->pdo->prepare($query);

        $statement->bindValue('name', $chapter['name'], PDO::PARAM_STR);
        $statement->bindValue('title', $chapter['title'], PDO::PARAM_STR);
        $statement->bindValue('description', $chapter['description'], PDO::PARAM_STR);
        $statement->bindValue('background_image', $chapter['background_image'], PDO::PARAM_STR);
        $statement->bindValue('background_image_alt', $chapter['background_image_alt'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    // /**
    //  * Update item in database
    //  */
    // public function update(array $item): bool
    // {
    //     $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `title` = :title WHERE id=:id");
    //     $statement->bindValue('id', $item['id'], PDO::PARAM_INT);
    //     $statement->bindValue('title', $item['title'], PDO::PARAM_STR);

    //     return $statement->execute();
    // }
}
