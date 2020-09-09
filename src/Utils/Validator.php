<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use function Symfony\Component\String\u;

/**
 * This class is used to provide an example of integrating simple classes as
 * services into a Symfony application.
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class Validator
{
    public function validateProjectname(?string $projectname): string
    {
        if (empty($projectname)) {
            throw new InvalidArgumentException('The projectname can not be empty.');
        }

        if (1 !== preg_match('/^[a-z_]+$/', $projectname)) {
            throw new InvalidArgumentException('The projectname must contain only lowercase latin characters and underscores.');
        }

        if (u($projectname)->trim()->length() < 6) {
            throw new InvalidArgumentException('The projectname must be at least 6 characters long.');
        }

        return $projectname;
    }

    public function validateProvidername(?string $providername): string
    {
        if (empty($providername)) {
            throw new InvalidArgumentException('The providername can not be empty.');
        }

        return $providername;
    }

    public function validateCompanyname(?string $companyname): string
    {
        if (empty($companyname)) {
            throw new InvalidArgumentException('The companyname can not be empty.');
        }


        return $companyname;
    }

    /*public function validateUsername(?string $username): string
    {
        if (empty($username)) {
            throw new InvalidArgumentException('The username can not be empty.');
        }

        if (1 !== preg_match('/^[a-z_]+$/', $username)) {
            throw new InvalidArgumentException('The username must contain only lowercase latin characters and underscores.');
        }

        return $username;
    }

    public function validatePassword(?string $plainPassword): string
    {
        if (empty($plainPassword)) {
            throw new InvalidArgumentException('The password can not be empty.');
        }

        if (u($plainPassword)->trim()->length() < 6) {
            throw new InvalidArgumentException('The password must be at least 6 characters long.');
        }

        return $plainPassword;
    }

    public function validateEmail(?string $email): string
    {
        if (empty($email)) {
            throw new InvalidArgumentException('The email can not be empty.');
        }

        if (null === u($email)->indexOf('@')) {
            throw new InvalidArgumentException('The email should look like a real email.');
        }

        return $email;
    }

    public function validateFullName(?string $fullName): string
    {
        if (empty($fullName)) {
            throw new InvalidArgumentException('The full name can not be empty.');
        }

        return $fullName;
    }*/
}
