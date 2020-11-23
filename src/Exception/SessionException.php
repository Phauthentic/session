<?php

declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace Phauthentic\Session;

use Exception;

/**
 * SessionException
 */
class SessionException extends Exception
{
    /**
     * @return static
     */
    public static function headersAlreadySent()
    {
        return new static(
            'Headers already sent. You can\'t set the session id anymore'
        );
    }

    /**
     * @return static
     */
    public static function couldNotStart()
    {
        return new static(
            'Could not start the session'
        );
    }

    /**
     * @return static
     */
    public static function alreadyStarted()
    {
        return new static(
            'Session was already started'
        );
    }
}
