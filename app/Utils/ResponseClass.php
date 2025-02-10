<?php

namespace App\Utils;

use Exception;

class ResponseClass extends Exception
{
  private $data;

  public function __construct($message, $code = 200, $data = null)
  {
    parent::__construct($message, $code);
    $this->data = $data;
  }

  public function getData()
  {
    return $this->data;
  }

  public function sendJsonResponse($spread = false)
  {
    $response = $spread
      ? $this->getData()
      : [
        'code' => $this->getCode(),
        'message' => $this->getMessage(),
        'data' => $this->getData(),
        'error' => false,
      ];
    return response()->json($response, $this->getCode());
  }
}
