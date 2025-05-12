<?php
namespace App\Service;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\RequestStack;

class LoggingMessage
{
   private RequestStack $requestStack;

   public function __construct(RequestStack $requestStack)
   {
        $this->requestStack = $requestStack;
   }

    function sendDebug($messageTxt):void
    {
		//Enregistre au fil de l'eau, les messages erreur dans  fichier
        $fichier_log = "../public/Logs/";
        
        $hdl=fopen($fichier_log.date_create("now")->format('Y-m-d').'-debug_boss.txt', 'a');

        $txt ="\r\n";
       // $txt .="\r\n".date_create("now")->format('d-m-Y H:i:s');
		$txt .="\r\n".$messageTxt;
        fwrite($hdl,$txt);
        fclose($hdl);
    }


}

