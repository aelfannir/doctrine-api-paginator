<?php


namespace AElfannir\DoctrineQueryPaginator;


use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoctrineQueryPaginatorBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}