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

namespace App\Tests\UI\Responder\Core;

use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Responder\Core\HomeResponder;
use App\UI\Responder\Core\Interfaces\HomeResponderInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
use Zend\Diactoros\Response;

/**
 * Class HomeResponderUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class HomeResponderUnitTest extends TestCase
{
    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
        $this->presenter = $this->createMock(PresenterInterface::class);
        $this->request = $this->createMock(ServerRequestInterface::class);

        $this->request->method('getAttribute')->willReturn('fr');
        $this->twig->method('getCharset')->willReturn('utf-8');
    }

    public function testItImplements()
    {
        $homeResponder = new HomeResponder($this->twig, $this->presenter);

        static::assertInstanceOf(
            HomeResponderInterface::class,
            $homeResponder
        );
    }

    public function testResponseIsReturned()
    {
        $homeResponder = new HomeResponder($this->twig, $this->presenter);

        static::assertInstanceOf(
            Response::class,
            $homeResponder($this->request)
        );
    }
}
