<?php

namespace App\Controller;

use App\Model\ChapterManager;

class ChapterController extends AbstractController
{
    /**
     * String Check
     */
    public function stringCheck(array $chapter, string $field, int $maxLength)
    {
         // var_dump('$errors'); die();
        if (!isset($chapter[$field]) || empty($chapter[$field])) {
            $errors[] = 'Le ' . $field . ' est obligatoire';
        }
        if (strlen($chapter[$field]) > $maxLength) {
            $errors[] = "Le $field ne doit pas dépasser $maxLength caractères";
        }
        if (!empty($errors)) {
        return $errors [0];
        }       
        
    }


        /**
     * Form Control
     */
    public function formControl(array $chapter): array
    {
        // TODO validations (length, format...)
        $errors = [];
        $errors[] = $this->stringCheck($chapter, 'name', 35);
        $errors[] = $this->stringCheck($chapter, 'title', 80);
        $errors[] = $this->stringCheck($chapter, 'description', 500);
        $errors[] = $this->stringCheck($chapter, 'background_image', 100);
        $errors[] = $this->stringCheck($chapter, 'background_image_alt', 100);
        return $errors;
    }

    /**
     * List chapters
     */
    public function adminIndex(): string
    {
        $chapterManager = new ChapterManager();
        $chapters = $chapterManager->selectAll('id');

        return $this->twig->render('Chapter/admin_index.html.twig', ['chapters' => $chapters]);
    }
    /**
     * Show admin informations for a specific chapter
     */
    public function adminShow(int $id): string
    {
        $chapterManager = new ChapterManager();
        $chapter = $chapterManager->selectOneById($id);

        return $this->twig->render('Chapter/admin_show.html.twig', ['chapter' => $chapter]);
    }

    /**
     * Edit a specific item
     */
    public function adminEdit(int $id): ?string
    {
        $chapterManager = new ChapterManager();
        $chapter = $chapterManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $chapter = array_map('trim', $_POST);
            $errors = $this->formControl($chapter);

            if (!empty($errors)) {
                return $this->twig->render('Chapter/_admin_form.html.twig', [
                    'errors' => $errors,
                ]);
            }


            // if validation is ok, update and redirection
            $chapterManager->adminUpdate($chapter);

            header('Location: /chapters/admin_show?id=' . $id);

            // we are redirecting so we don't want any content rendered
            return null;
        }
        return $this->twig->render('Chapter/admin_edit.html.twig', [
            'chapter' => $chapter,
        ]);
    }

    /**
     * Add a new item
     */
    public function adminAdd(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $chapter = array_map('trim', $_POST);
            $errors = $this->formControl($chapter);


            // TODO validations (length, format...)

            if (!empty($errors)) {
                return $this->twig->render('Chapter/_admin_form.html.twig', [
                    'errors' => $errors,
                ]);
            }

            // if validation is ok, insert and redirection
            $chapterManager = new ChapterManager();
            $id = $chapterManager->adminInsert($chapter);

            header('Location:/chapters/admin_show?id=' . $id);
            return null;
        }

        return $this->twig->render(
            'Chapter/admin_add.html.twig'
        );
    }

    // /**
    //  * Delete a specific item
    //  */
    // public function delete(): void
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $id = trim($_POST['id']);
    //         $itemManager = new ItemManager();
    //         $itemManager->delete((int)$id);

    //         header('Location:/items');
    //     }
    // }
}
