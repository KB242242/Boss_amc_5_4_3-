<?php
namespace App\Service;

use PDO;
use App\Service\LoggingMessage;
use Symfony\Component\Yaml\Yaml;
use App\Service\ConfigurationNodes;


class PDOutilities
{
    private $pdo = null;

    public function __construct(
        LoggingMessage $logger)
   {
    $this->pdo = new PDO('mysql:host=localhost;dbname=boss_amc', 'root', '');   
   }

   function select($qr){
        $statement = $this->pdo->query($qr);
        $results = $statement->fetchAll();
        return $results;
   }
   function ticket($table,$champ){
        $qr =	"SELECT MAX(".$champ .")as ticket FROM ".$table;
        $statement = $this->pdo->query($qr);
        $results = $statement->fetchAll();
        return $results[0]['ticket'];
   }
   function selectOneField($qr,$champ){
     $statement = $this->pdo->query($qr);
     $results = $statement->fetchAll();
    // if(empty($results[0]) || is_null($results[0])){ return false;}
     return $results[0][$champ];
}
function selectOneEntity($qr){
     $statement = $this->pdo->query($qr);
     $results = $statement->fetchAll();
    // if(empty($results[0]) || is_null($results[0])){ return false;}
     return $results[0];
}
     public function prepare($query){
          // Ne concerne pas les requetes de selection
          return  $this->pdo->prepare($query);
     }
}