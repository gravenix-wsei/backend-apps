<?php declare(strict_types=1);

namespace App\Core\Content\Response;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SuccessResponse extends AbstractApiResponse
{
    public function __construct()
    {
        parent::__construct();
        $this->object['success'] = true;
    }
}