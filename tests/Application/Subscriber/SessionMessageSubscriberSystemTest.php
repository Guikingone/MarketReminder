<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Application\Subscriber;

use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class SessionMessageSubscriberSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class SessionMessageSubscriberSystemTest extends KernelTestCase
{
    use TestCaseTrait;
}