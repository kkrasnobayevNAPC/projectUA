<?php

class PasswordHelper
{
    /**
     * @param int $length
     * @return string
     * @throws Exception
     */
    public static function generatePassword(int $length = 8): string
    {

        if ($length < 1) {
            throw new Exception('$length must be more than 0');
        }

        $chars = 'abcdfhjkmnrstvwzABCDFGJNQRSUVWXYZ123456789';

        $charsLength = strlen($chars) - 1;

        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, $charsLength)];
        }

        return $password;

    }

    /**
     * @param string $userPassword
     * @param string $databaseHash
     * @return bool
     */
    public static function verifyPassword(string $userPassword, string $databaseHash): bool
    {
        return password_verify($userPassword, $databaseHash);
    }

}