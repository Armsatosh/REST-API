<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Article;
use App\Form\ArticleType;
/**
 * Article controller.
 * @Route("/api", name="api_")
 */
class ArticleController extends FOSRestController
{
    /**
     * Lists all  Articles.
     * @Rest\Get("/articles")
     *
     * @return Response
     */
    public function getMovieAction()
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findall();
        return $this->handleView($this->view($articles));
    }
    /**
     * Lists all  Articles.
     * @Rest\Get("/articles/{id}")
     *
     * @return Response
     */
    public function getMovieIdAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findById($id);
        return $this->handleView($this->view($articles));
    }
    /**
     * Lists all  Articles.
     * @Rest\Delete("/articles/{id}")
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getReference(Article::class, $id);
        $em->remove($articles);
        $em->flush();
    }
    /**
     * Create Article.
     * @Rest\Post("/article")
     *
     * @return Response
     */
    public function postMovieAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }
}