<?php

namespace App\Helpers;

use App\Models\User;

class Helper
{
    /**
     * set default success status
     */
    const SUCCESS = 'success';

    /**
     * set default error status
     */
    const ERROR = 'error';

    /**
     * Generate unique username
     * Username format: {first name initial}{last name}
     * Example result: Juan Dela Cruz => jdelacruz
     *
     * @param [string] $first_name
     * @param [string] $last_name
     * @return string
     */
    public static function username($first_name, $last_name)
    {
        $result = null;
        $suffix = 0;

        try {

            // if first_name/last_name is empty, throw null
            if (trim($first_name) == null || trim($first_name) == null)
                return $result;

            // replace ñ to n
            $first_name = str_replace('ñ', 'n', $first_name);
            $last_name = str_replace('ñ', 'n', $last_name);

            // replace Ñ to n
            $first_name = str_replace('Ñ', 'n', $first_name);
            $last_name = str_replace('Ñ', 'n', $last_name);

            do {
                //set the first name to small letters
                $first_name = strtolower(trim($first_name));

                //get the first character of the first name
                $first_character = substr($first_name, 0, 1);

                //set the last name to small letters
                $last_name = strtolower(trim($last_name));

                // remove spaces between word
                $lastname = str_replace(' ', '', $last_name);

                //concat first name first character and the last name
                $result = sprintf('%s%s', $first_character, $lastname);

                // check if username is unique
                $user_count = User::where('username', $result)->count();

                // if username is existing add suffix
                if ($user_count > 0) {
                    $suffix++;
                    $result = sprintf('%s%u', $result, $suffix);

                    // recheck if username is existing
                    $user_count = User::where('username', $result)->count();
                }
            } while ($user_count > 0);
        } catch (\Throwable $th) {
            return $result;
        }

        return $result;
    }

    /**
     * default messages by http codes
     *
     * @param integer $httpCode
     *  $httpCode 201 => Created | <br />
     *  $httpCode 200 => Updated | <br />
     *  $httpCode 204 => Deleted | <br />
     * @return string
     */
    public static function message(?int $httpCode = null): string
    {
        $message = null;

        switch ($httpCode) {
            case 201:
                $message = 'Successfully created!';
                break;

            case 200:
                $message = 'Successfully updated!';
                break;

            case 204:
                $message = 'Successfully deleted!';
                break;

            default:
                $message = '';
                break;
        }

        return $message;
    }
}
