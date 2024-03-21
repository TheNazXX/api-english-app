<?php 

namespace App\Http\Actions\Word;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;
use App\DB;

class StoreAction { 

  public function __construct(private DB $db){
    $this->db = $db;
  }

  public function __invoke(ServerRequestInterface $request, callable $next){

    $data = $request->getParsedBody();

    $this->db->query("
      INSERT INTO words (en, translate, date_added, unit_id, partOfSpeech_id, synonyms, sentences, difficult) 
      VALUES (:en, :translate, :date_added, :unit_id, :partOfSpeech_id, :synonyms, :sentences, :difficult)",
      $this->prepareDataForDb($data)
    );
    
    return (new JsonResponse(['message' => 'The word was successfully added']))->withStatus(200);
  }

  public function prepareDataForDb($data){
    return [
      'en' => $data['en'],
      'translate' => json_encode($data['translate']),
      'date_added' => date('Y-m-d'),
      'unit_id' => null,
      'partOfSpeech_id' => $this->partsOfSpeech_id($data['partOfSpeech']),
      'synonyms' => isset($data['synonyms']) && !empty($data['synonyms']) ? json_encode($data['synonyms']) : null,
      'sentences' =>  isset($data['sentences']) && !empty($data['sentences'])  ? json_encode($data['sentences']) : null,
      'difficult' => 0
    ];
  }

  public function partsOfSpeech_id(string $partOfSpeech){
    switch ($partOfSpeech){
      case '-': return null;
      case 'noun': return 1;
      case 'verb': return 2;
      case 'adjective': return 3;
      case 'adverb': return 4;
      case 'conjunction': return 5;
    }
  }
  
}