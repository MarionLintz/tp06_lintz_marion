<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Firebase\JWT\JWT;
use App\Models\Utilisateur;

class UserController
{
    public function login(Request $request, Response $response, array $args): Response
    {
        global $entityManager;
        $data = $request->getParsedBody();   

        $login = $data["login"] ?? ""; 
        $password = $data["password"] ?? ""; 

        $utilisateurRepository = $entityManager->getRepository('Utilisateur');
        $utilisateur = $utilisateurRepository->findOneBy(array('login' => $login, 'password' => $password));
        if ($utilisateur and $login == $utilisateur->getLogin() and $password == $utilisateur->getPassword()) {
            $user = array('Nom' => $utilisateur->getNom(), 'Prenom' => $utilisateur->getPrenom());
            $dateNow = time();

            $payload = [
                "iat" => $dateNow,
                "exp" => $dateNow + 100,
                "user" => $user
            ];

            $token_jwt = JWT::encode($payload, $_ENV["JWT_SECRET"], "HS256");

            $response->getBody()->write(json_encode([
                    "success" => true,
                    "user" => $user
                ]));

            return $response
                ->withHeader("Authorization", $token_jwt)
                ->withHeader("Content-Type", "application/json");
        }
        else{
            $response->getBody()->write(json_encode([
                "success" => false
            ]));
            return $response->withHeader("Content-Type", "application/json");
        }
    }

    public function register(Request $request, Response $response, array $args): Response
    {
        global $entityManager;
        $data = $request->getParsedBody();

        $result = [
            "success" => true,
            "data" => $data
        ];

        $entityManager->getConnection()->beginTransaction();

        try{
            $client = new Utilisateur;
            $client->setLogin($data.login);
            $client->setPassword($data.password);
            $client->setNom($data.nom);
            $client->setPrenom($data.prenom);
            $client->setCivilite($data.civilite);
            $client->setAdresse($data.adresse_num.' '.$data.adresse_rue.' '.$data.adresse_cp.' '.$data.adresse_ville);
            $client->setPays($data.adresse_pays);
            $client->setTelephone($data.tel);
            $client->setMail($data.email);

            $entityManager->persist($client);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        }
        catch(Exception $e){
            $entityManager->getConnection()->rollback();
            throw $e;
        }

        $response->getBody()->write(json_encode($result));
        return $response->withHeader("Content-Type", "application/json");
    }

}