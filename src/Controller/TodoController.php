<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TodoController
 * @package App\Controller
 * @Route("todo")
 */
class TodoController extends AbstractController
{
    /**
     * @Route("/", name="todo")
     */
    public function index(SessionInterface $session): Response
    {
        if(! $session->has('todos')) {
            $todos = [
                'lundi' => 'HTML',
                'mardi' => 'CSs',
                'mercredi' => 'Js',
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "Bienvenu dans votre plateforme de gestion des todos");
        }
        return $this->render('todo/index.html.twig');
    }

    /**
     * @Route("/add/{name}/{content}", name="addTodo")
     */
    public function addTodo($name, $content, SessionInterface $session) {

        // Vérifier que ma session contient le tableau de todo
        if (!$session->has('todos')) {
            //ko => messsage erreur + redirection
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        } else {
            //ok
            // Je vérifie si le todo existe
            $todos = $session->get('todos');
            if (isset($todos[$name])) {
                //ko => messsage erreur + redirection
                $this->addFlash('error', "Le todo $name existe déjà");
            } else {
                //ok => j ajoute et je redirige avec message succès
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo $name a été ajouté avec succès");
            }
        }
        return $this->redirectToRoute('todo');
    }

    /**
     * @Route("/modify/{name}/{content}", name="modifytodo")
     */
    public function modifyTodo($name, $content, SessionInterface $session) {

        if (!$session->has('todos')) {
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        } else {
            $todos = $session->get('todos');
            if (!isset($todos[$name])) {
                $this->addFlash('error', "Le todo $name n'existe pas");
            } else {
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo $name a été modifié avec succès");
            }
        }
        return $this->redirectToRoute('todo');
    }


    /**
     * @Route("/delete/{name}", name="deletetodo")
     */
    public function deleteTodo($name, SessionInterface $session) {

        if (!$session->has('todos')) {
            $this->addFlash('error', "La liste des todos est vide");
        } else {
            $todos = $session->get('todos');
            if (!isset($todos[$name])) {
                $this->addFlash('error', "Le todo $name n'existe pas");
            } else {
                unset($todos[$name]);
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo $name a été supprimé avec succès");
            }
        }
        return $this->redirectToRoute('todo');
    }

    /**
     * @Route("/reset", name="resettodo")
     */
    public function reset(SessionInterface $session): Response
    {
        if (!$session->has('todos')) {
            $this->addFlash('error', "La liste des todos est vide");
        }
        else{
            $todos = [
                'lundi' => 'HTML',
                'mardi' => 'CSs',
                'mercredi' => 'Js',
                ];
            $session->set('todos', $todos);
            $this->addFlash('success', "Le todo a été réinisialiser avec succès");
            return $this->redirectToRoute('todo');
            }
    }
}


