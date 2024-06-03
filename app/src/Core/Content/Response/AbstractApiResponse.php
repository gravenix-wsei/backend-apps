<?php declare(strict_types=1);

namespace App\Core\Content\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

abstract class AbstractApiResponse extends Response
{
    protected \ArrayObject $object;

    protected string $type = 'array_object';

    protected int $statusCode = Response::HTTP_OK;

    public function __construct(int $statusCode = Response::HTTP_OK, array $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->object = new \ArrayObject();
        parent::__construct('', $this->statusCode, $headers);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAllData(): array
    {
        return $this->object->getArrayCopy();
    }

    public function formatResponse(): Response
    {
        $arrayObject = [
            'data' => $this->object->getArrayCopy(),
            'type' => $this->type,
        ];

        return new JsonResponse($arrayObject, $this->statusCode, $this->headers->all());
    }
}