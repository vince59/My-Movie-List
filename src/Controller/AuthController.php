<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Users;

class AuthController extends AbstractController
{

    public function login_view()
    {
        $error="";
        return $this->render('auth/login.html.twig',['error' => $error,'name' => '']);
    }

    public function register_view()
    {
        $error="";
        return $this->render('auth/register.html.twig',['error' => $error,'name' => '']);
    }

    public function register(Request $request) {
        $user = new Users();
        $user->setEmail($_POST['email']);
        $user->setName($_POST['username']);
        $user->setPassword($_POST['password']);
        $error="";
        try {
            $user->validate();
        } catch (\Exception $e) {
            return $this->render('auth/register.html.twig',['error' => $e->getMessage(),'name' => '']);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush(); 
        $this->get('session')->set('id', $user->getId());
        return $this->redirectToRoute('app_home_show');
    }

    public function logout(Request $request) {
        $this->get('session')->remove('id');  
        return $this->redirectToRoute('app_home_show');      
    }

    public function unRegister() {
        $id = $this->get('session')->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
                ->getRepository(Users::class)
                ->find($id);

        foreach ($user->getLists() as $list) {
            foreach ($list->getMovies() as $movie) {
                $list->removeMovie($movie);
                $entityManager->remove($movie);
            }
            $entityManager->remove($list);
            $user->removeList($list);
        }
        $entityManager->remove($user);
        $entityManager->flush();

        $this->get('session')->remove('id');  
        return $this->redirectToRoute('app_home_show'); 
    }

    public function login(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $query=$queryBuilder->select('u')
        ->from(Users::class, 'u')
        ->where('u.email = :email')
        ->setParameter('email',$_POST['email'])->getQuery();

        $user = $query->getOneOrNullResult();
        if ($user!=null && $user->getPassword()==$_POST['password']) {
            $this->get('session')->set('id', $user->getId());
            return $this->redirectToRoute('app_home_show');
        } else 
            return $this->render('auth/login.html.twig',['error' => 'Incorrect login','name' => '']);
    }

    public function persoShow() {
        $id = $this->get('session')->get('id');
        $user = $this->getDoctrine()
                ->getRepository(Users::class)
                ->find($id);
        return $this->render('auth/editpersonal.html.twig',['error' => '','name' => $user->getName(), 'email' => $user->getEmail(), 'password' => $user->getPassword()]);
    }

    public function persoEdit() {
        $id = $this->get('session')->get('id');
        $user = $this->getDoctrine()
                ->getRepository(Users::class)
                ->find($id);

        $user->setEmail($_POST['email']);
        $user->setName($_POST['username']);
        $user->setPassword($_POST['password']);
        $error="";
        try {
            $user->validate();
        } catch (\Exception $e) {
            return $this->render('auth/editpersonal.html.twig',['error' => $e->getMessage(),'name' => '']);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_home_show');
    }

}
