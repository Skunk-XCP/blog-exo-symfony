<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
//on crée une classe Article
class Article
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */

//    on crée une entité à laquelle on va mapper les annotations grâce à l'ORM
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $title;
}