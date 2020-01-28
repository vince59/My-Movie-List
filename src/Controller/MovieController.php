<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Users;
use App\Entity\MoviesList;
use App\Entity\Movies;

class MovieController extends AbstractController
{

    public function show()
    {
        $id = $this->get('session')->get('id');
        if (isset($id)) {
            $user = $this->getDoctrine()
                ->getRepository(Users::class)
                ->find($id);

            return $this->render('movies/newlist.html.twig',['name' => $user->getName()]);
        }
        else {
            return $this->render('movies/newlist.html.twig',['name' => ""]);
        }
    }

    public function saveList()
    {
        $list = $this->getDoctrine()
                ->getRepository(MoviesList::class)
                ->find($_POST['id']);

        $list->setName($_POST['name']);
        $list->setComment($_POST['comment']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($list);
        $entityManager->flush();
        return $this->redirectToRoute('app_movie_view');
    }

    public function editList()

    {
        $id = $this->get('session')->get('id');
        if (isset($id)) {
            $user = $this->getDoctrine()
                ->getRepository(Users::class)
                ->find($id);

            $list = $this->getDoctrine()
            ->getRepository(MoviesList::class)
            ->find($_GET['id']);
            return $this->render('movies/editlist.html.twig',['name' => $user->getName(),
                                                            'lname' => $list->getName(),
                                                            'lid' => $list->getId(),
                                                            'lcomment' => $list->getComment()]);
        }
        else {
            return $this->render('movies/newlist.html.twig',['name' => ""]);
        }
    }

    public function deleteList()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $list = $entityManager->getRepository(MoviesList::class)
        ->find($_GET['id']);
        $entityManager->remove($list);
        $entityManager->flush();
        return $this->redirectToRoute('app_movie_view');
    }

    public function viewList()
    {
        $id = $this->get('session')->get('id');
        if (isset($id)) {
            $user = $this->getDoctrine()
                ->getRepository(Users::class)
                ->find($id);

            return $this->render('movies/viewlist.html.twig',['name' => $user->getName(),'lists' => $user->getLists()]);
        }
        else {
            return $this->render('movies/viewlist.html.twig',['name' => ""]);
        }
    }

    public function defaultList() {
        $this->get('session')->set('defaultIdList', $_GET['id']);
        $this->get('session')->set('defaultNameList', $_GET['name']);
        return $this->redirectToRoute('app_movie_view');
    }

    public function newList()
    {
        $list = new MoviesList();
        $list->setComment($_POST['comment']);
        $list->setName($_POST['name']);

        $user = $this->getDoctrine()
                ->getRepository(Users::class)
                ->find($this->get('session')->get('id'))
                ->addList($list);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($list);
        $entityManager->persist($user);
        $entityManager->flush(); 
        
        return $this->redirectToRoute('app_movie_view');
    }

    public function addMovie() {
        $entityManager = $this->getDoctrine()->getManager();
        $list = $entityManager->getRepository(MoviesList::class)
        ->find($_GET['listId']);
        $movie = new Movies();
        $movie->setTitle($_GET['title']);
        $movie->setPoster($_GET['poster']);
        $movie->setBddId($_GET['movieId']);
        $movie->setOverview('');
        $list->addMovie($movie);
        $entityManager->persist($movie);
        $entityManager->persist($list);
        $entityManager->flush(); 
        
        return $this->redirectToRoute('app_home_show');

    }

    public function showDetail() {  
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.themoviedb.org/3/movie/".$_GET['id']."?language=en-US&api_key=a6de68dc2eef10118d8401cb1a7dc24c",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "{}"));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        $movie=[];
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $movie=json_decode($response,true);
            if ($movie==null) return $this->redirectToRoute('app_movie_view');
            $id = $this->get('session')->get('id');
            if (isset($id)) {
                $user = $this->getDoctrine()
                    ->getRepository(Users::class)
                    ->find($id);
                return $this->render('movies/detail.html.twig',['name' => $user->getName(), 'movie' => $movie ]);
            } else return $this->render('movies/detail.html.twig',['name' => '', 'movie' => $movie ]);

        }  
    }

    public function deleteMovie() {
        $entityManager = $this->getDoctrine()->getManager();
        $list = $entityManager->getRepository(MoviesList::class)
        ->find($_GET['listId']);
        $movie = $entityManager->getRepository(Movies::class)
        ->find($_GET['movieId']);
        $list->removeMovie($movie);
        $entityManager->flush();
        return $this->redirectToRoute('app_movie_view');
    }

}
