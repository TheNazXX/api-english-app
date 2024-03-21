<?php

namespace App\Http\Actions\Words;

use Laminas\Diactoros\Response\JsonResponse;
use App\DB;

class IndexAction
{

  private $db;

  public function __construct(DB $db){
    $this->db = $db;
  }

  public function __invoke(){
    $data = $this->getWords();
    return new JsonResponse($data);
  }

  public function getWords(){
    return $this->db->query("SELECT en FROM words")->findAll();
  }

  public function prepareResponseData($data){

    $responseData = [];
    
    foreach($data as $elem){
      $responseData[] = [
        'en' => $elem['en']
        // 'translate' => json_decode($elem['translate']),
        // 'partOfSpeech' => $elem['partOfSpeech'],
        // 'unit' => $elem['unit_id'],
        // 'synonyms' => isset($elem['synonyms']) ? json_decode($elem['synonyms']) : null,
        // 'sentences' => isset($elem['sentences']) ? json_decode($elem['sentences']) : null,
        // 'difficult' => $elem['difficult']
      ];
    };
    
    return $responseData;
  }
}