<?php declare(strict_types=1);

namespace App\Core\Content\Response\User;

use App\Core\Content\Response\AbstractApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SuccessLoginResponse extends AbstractApiResponse
{
    public function __construct(TokenInterface $token)
    {
        parent::__construct(Response::HTTP_CREATED, ['Set-Cookie' => 'accessToken=dupa']);
    }
}