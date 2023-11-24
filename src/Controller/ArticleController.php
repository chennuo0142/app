<?php

namespace App\Controller;

use ErrorException;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\UserSettingRepository;
use App\Service\UpdateUserSettingService;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\throwException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/gestion')]
class ArticleController extends AbstractController
{
    private $updateUserSettingService;
    private $sluggerInterface;

    public function __construct(UpdateUserSettingService $updateUserSettingService, SluggerInterface $sluggerInterface)
    {
        $this->updateUserSettingService = $updateUserSettingService;
        $this->sluggerInterface = $sluggerInterface;
    }

    #[Route('/article', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findByUser($this->getUser());

        $this->updateUserSettingService->update('Article', $articleRepository);

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $article->setUser($this->getUser())
                ->setSlug(strtolower($this->sluggerInterface->slug($article->getName())));
            $articleRepository->add($article, true);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/article/{slug}', name: 'app_article_show', methods: ['GET'])]
    public function show(ArticleRepository $articleRepository, $slug): Response
    {
        $article = $articleRepository->findOneBySlug($slug);


        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/{slug}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit($slug, Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findOneBySlug($slug);

        if (!$article) {
            throw new Exception("L'article n'existe pas");
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //mise a jour le slug si modification
            $article->setSlug(strtolower($this->sluggerInterface->slug($article->getName())));

            $articleRepository->add($article, true);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/article/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);

            $this->updateUserSettingService->update('Article', $articleRepository);
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
