<?php

class PasswordHelper
{

    /**
     * While the old variant of generatePassword did the job, I found it overcomplicated
     * and simplified it while adding a value guard
     * @param int $length
     * @return string
     * @throws Exception
     */
    public static function generatePassword(int $length = 8): string
    {

        if ($length < 2) {
            throw new Exception('$length must be more than 1');
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
     * According to the documentation password_verify is the best function to use, if password
     * password_hash - which was initially the case
     * was encrypted with
     * @param string $userPassword
     * @param string $databaseHash
     * @return bool
     */
    public static function verifyPassword(string $userPassword, string $databaseHash): bool
    {
        return password_verify($userPassword, $databaseHash);
    }

    //xxxxxxxxxxxxxx

}