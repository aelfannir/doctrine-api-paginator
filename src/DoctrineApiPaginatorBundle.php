<?php


namespace Aelfannir\DoctrineApiPaginator;


use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoctrineApiPaginatorBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}