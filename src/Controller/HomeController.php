<?php
namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Users;

class HomeController extends AbstractController
{
    public function show()
    {
        $id = $this->get('session')->get('id');
        if (isset($id)) {
            $user = $this->getDoctrine()
                ->getRepository(Users::class)
                ->find($id);

            return $this->render('home.html.twig',['name' => $user->getName()]);
        }
        else {
            return $this->render('home.html.twig',['name' => ""]);
        }
    }

    public function show_result() 
    {
        $client = HttpClient::create();
        $rep = $client->request('GET', 'https://api.themoviedb.org/3/search/movie?api_key=a6de68dc2eef10118d8401cb1a7dc24c&language=en-US&query='.$_POST['search'].'&page=1&include_adult=false');
        $response = $rep->getContent();
        $movies=[];
        
        $list=json_decode($response,true);    
        $i=0;
        foreach ($list['results'] as $key => $value) {
            $movies[$i]=[];
            $movies[$i]['imglink']='https://image.tmdb.org/t/p/w500/'.$value['poster_path'];
            $movies[$i]['title']=$value['title'];
            $movies[$i]['overview']=$value['overview'];
            $movies[$i++]['id']=$value['id'];
        }  
        
        $defaultIdList = $this->get('session')->get('defaultIdList');
        $defaultNameList = $this->get('session')->get('defaultNameList');
        if (!isset($defaultIdList)) {
            $defaultIdList='';
            $defaultNameList='';
        }
        $id = $this->get('session')->get('id');
        if (isset($id)) {
            $user = $this->getDoctrine()
                ->getRepository(Users::class)
                ->find($id);
            return $this->render('result.html.twig',['name' => $user->getName(), 'movies' => $movies,'lists' => $user->getLists(),'search' => $_POST['search']]);
        }
        else {
            return $this->render('result.html.twig',['name' => "",'movies' => $movies,'defaultIdList' => $defaultIdList, 'defaultNameList' => $defaultNameList,'search' => $_POST['search']]);
        }
    }
}
