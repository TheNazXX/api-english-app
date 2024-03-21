<?php 

namespace App\Http\Actions\Word;

use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;
use App\DB;

class IndexAction { 

  public function __construct(private DB $db){
    $this->db = $db;
  }

  public function __invoke(ServerRequestInterface $request, callable $next){
    
    $word = $request->getQueryParams()['word'];

    $data = $this->db->query("SELECT words.*, parts_of_speech.type_name AS partOfSpeech FROM words JOIN parts_of_speech ON words.partOfSpeech_id = parts_of_speech.type_id WHERE en = '$word'")->findAll();
 
    return (new JsonResponse($this->prepareReponseWord($data[0])))->withStatus(200);
  }


  public function prepareReponseWord($data){
    return [
      'en' => $data['en'],
      'translate' => json_decode($data['translate']),
      'partOfSpeech' => $data['partOfSpeech'],
      'unit' => $data['unit_id'],
      'synonyms' => isset($data['synonyms']) ? json_decode($data['synonyms']) : null,
      'sentences' => isset($data['sentences']) ? json_decode($data['sentences']) : null,
      'difficult' => $data['difficult']
    ];
  }
};