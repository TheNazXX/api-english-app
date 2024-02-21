<?php

namespace Framework\Http;


class Request 
{
  private $queryParams = [];
  private $parsedBody;



  public function getQueryParams()
  {
    return $this->queryParams;
  }

  public function withQueryParams(array $query)
  {
    $new = clone $this;
    $new->queryParams = $query;
    return $new; 
  }

  public function getParsedBody()
  {
    return $this->parsedBody;
  }

  public function withParsedBody($data)
  {
    $new = clone $this;
    $new->parsedBody = $data;
    return $new;
  }
}