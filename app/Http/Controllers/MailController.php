<?php

namespace App\Http\Controllers;
use Webklex\IMAP\Client;
use Illuminate\Http\Request;
use DB;

class MailController extends Controller
{
    private $BDDmail;

    public function listerMails()
    {
        $this->BDDmail = DB::connection('BDDmail');
        $oClient = \Webklex\IMAP\Facades\Client::account('default');
        $oClient->connect();

        $aFolder = $oClient->getFolders();

        foreach($aFolder as $oFolder){
        $i = 0;
            $aMessage = $oFolder->query()->all()->get();
            foreach($aMessage as $oMessage){
                
                $objet = $oMessage->getSubject();
                $contenuHTML = $oMessage->getHTMLBody(true);
                $contenuTEXT = $oMessage->getTextBody();
                $date = $oMessage->getDate();
                $expediteur = $oMessage->getFrom();
                    $expediteurHost = $expediteur[$i]->host;
                    $expediteurPersonal = $expediteur[$i]->personal;
                    $expediteurMail = $expediteur[$i]->mail;
                    $expediteurFullMail = $expediteur[$i]->full;

                
                    $this->BDDmail->select(
                        "insert into mail (objet, expediteur, mailFull, mailPersonal, contenuHTML, contenuTEXT, date, host) 
                        values (:objet, :expediteurMail, :expediteurFullMail, :expediteurPersonal, :contenuHTML, :contenuTEXT, :date, :expediteurHost)",[
                            'objet' => $objet, 
                            'contenuHTML' => $contenuHTML, 
                            'contenuTEXT' => $contenuTEXT,
                            'date' => $date, 
                            'expediteurMail' => $expediteurMail,
                            'expediteurPersonal' => $expediteurPersonal,
                            'expediteurFullMail' => $expediteurFullMail,
                            'expediteurHost' => $expediteurHost,
                        ]);
                   

                if($oMessage->moveToFolder('INBOX.read') == true){
                    echo 'Message has ben moved';
                }else{
                    echo 'Message could not be moved';
                }
                $i++;
            }
        }

        $lesMails =$this->BDDmail->select('SELECT mail.id, HOST, contact.id as contact, societe, SUBSTR(date, 1, 4) AS annee, SUBSTR(date, 6, 5) AS jour, SUBSTR(date, 12, 5) AS heure, expediteur, objet, contenuHTML, contenuTEXT, mailFull, mailPersonal
        FROM mail
        LEFT OUTER JOIN  contact ON mail.expediteur = contact.mail
        LEFT OUTER JOIN  client ON contact.id_client = client.id
        WHERE mail.HOST  NOT LIKE "gmail.com"
        AND mail.HOST  NOT LIKE "orange.fr"
        AND mail.HOST  NOT LIKE "wanadoo.fr"
        AND mail.HOST  NOT LIKE "yahoo.com"
        ORDER BY date desc limit 100');

        $listeClient = $this->BDDmail->select('SELECT societe, id FROM client group by societe, id');

        return view('listeMails', [
            'lesMails' => $lesMails,
            'listeClient' => $listeClient,
        ]);
    }

    public function filtreSoci()
    {
        $this->BDDmail = DB::connection('BDDmail');
        
        $filtreSoci =$this->BDDmail->select('SELECT mail.id, societe, contact.id as contact, SUBSTR(date, 1, 4) AS annee, SUBSTR(date, 6, 5) AS jour, SUBSTR(date, 12, 5) AS heure, expediteur, objet, contenuHTML, contenuTEXT, mailFull, mailPersonal
        FROM mail, contact, client
        WHERE mail.expediteur = contact.mail
        AND contact.id_client = client.id
        AND mail.HOST  NOT LIKE "gmail.com"
        AND mail.HOST  NOT LIKE "orange.fr"
        AND mail.HOST  NOT LIKE "wanadoo.fr"
        AND mail.HOST  NOT LIKE "yahoo.com"
        ORDER BY date desc limit 100');

        return [           
            'filtreSoci' => $filtreSoci,
        ];
    }

    public function filtreNonSoci()
    {
        $this->BDDmail = DB::connection('BDDmail');
        
        $filtreNonSoci =$this->BDDmail->select('SELECT mail.id, societe, contact.id as contact, SUBSTR(date, 1, 4) AS annee, SUBSTR(date, 6, 5) AS jour, SUBSTR(date, 12, 5) AS heure, expediteur, objet, contenuHTML, contenuTEXT, mailFull, mailPersonal
        FROM mail
        LEFT OUTER JOIN  contact ON mail.expediteur = contact.mail
        LEFT OUTER JOIN  client ON contact.id_client = client.id
        WHERE societe IS null
        AND mail.HOST  NOT LIKE "gmail.com"
        AND mail.HOST  NOT LIKE "orange.fr"
        AND mail.HOST  NOT LIKE "wanadoo.fr"
        AND mail.HOST  NOT LIKE "yahoo.com"
        ORDER BY date desc limit 100');

        return [           
            'filtreNonSoci' => $filtreNonSoci,
        ];
    }

    public function noFiltre()
    {
        $this->BDDmail = DB::connection('BDDmail');

        $noFiltre =$this->BDDmail->select('SELECT mail.id, host, contact.id as contact, societe, SUBSTR(date, 1, 4) AS annee, SUBSTR(date, 6, 5) AS jour, SUBSTR(date, 12, 5) AS heure, expediteur, objet, contenuHTML, contenuTEXT, mailFull, mailPersonal
        FROM mail
        LEFT OUTER JOIN  contact ON mail.expediteur = contact.mail
        LEFT OUTER JOIN  client ON contact.id_client = client.id
        WHERE mail.HOST  NOT LIKE "gmail.com"
        AND mail.HOST  NOT LIKE "orange.fr"
        AND mail.HOST  NOT LIKE "wanadoo.fr"
        AND mail.HOST  NOT LIKE "yahoo.com"
        ORDER BY date desc limit 100');

        return[
            'noFiltre' => $noFiltre,
        ];
    }

    public function ajoutContact()
    {

        $this->BDDmail = DB::connection('BDDmail');

        request()->validate([
            'prenom' => ['required'],
            'nom' => ['required'],
            'email' => ['required'],
            'fonction' => ['required'],
        ]);
        
        $prenom = request('prenom');
        $nom = request('nom');
        $email = request('email');
        $fonction = request('fonction');
        $client = request('client');
        $nomSociete = request('nomSociete');
        $afficheClient = request('afficheClient');

        if($afficheClient){
            $this->BDDmail->select("insert into client (societe) values (:nomSociete)",['nomSociete' => $nomSociete]);
            $idSociete = $this->BDDmail->select("select id from client where societe like :nomSociete",['nomSociete' => $nomSociete]);

            dump($idSociete);
            $idSociete = $idSociete[0] -> id;
            dump($idSociete);
            $this->BDDmail->select(
                "insert into contact (prenom, nom, mail, fonction, id_client) 
                values (:prenom, :nom, :email, :fonction, :idSociete)",[
                    'prenom' => $prenom, 
                    'nom' => $nom, 
                    'email' => $email,
                    'fonction' => $fonction, 
                    'idSociete' => $idSociete,
            ]);
        }
        else{
            $this->BDDmail->select(
                "insert into contact (prenom, nom, mail, fonction, id_client) 
                values (:prenom, :nom, :email, :fonction, :client)",[
                    'prenom' => $prenom, 
                    'nom' => $nom, 
                    'email' => $email,
                    'fonction' => $fonction, 
                    'client' => $client,
                ]);
        }
    }

    public function supprimerMail()
    {
        $idMail = request('idMail');

        $this->BDDmail->select(
            "UPDATE mail
            SET archiver = 1
            WHERE id = 5",['idMail' => $idMail]);
    }
}
