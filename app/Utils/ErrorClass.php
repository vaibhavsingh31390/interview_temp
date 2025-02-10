<?php

namespace App\Utils;

use Exception;
use Illuminate\Support\Facades\App;

class ErrorClass extends Exception
{
  private $data;

  public function __construct($message, $code = 500, $data = null)
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
        'error' => true,
      ];
    if (!App::environment('live')) {
      $response['trace'] = $this->getTrace();
    }
    return response()->json($response, $this->getCode());
  }
}
