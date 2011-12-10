<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A User
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="users"
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="league_seq", initialValue=1, allocationSize=100)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length="255")
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length="255")
     * @Assert\Email
     */
    protected $email;

    protected $password;

    /**
     * @ORM\ManyToMany(targetEntity="League", inversedBy="users")
     */
    protected $leagues;
    protected $picks;
}
