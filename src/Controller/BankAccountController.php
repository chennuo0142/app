<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Form\BankAccountType;
use App\Repository\BankAccountRepository;
use App\Service\UpdateUserSettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestion')]
class BankAccountController extends AbstractController
{
    private $updateUserSettingService;

    public function __construct(UpdateUserSettingService $updateUserSettingService)
    {
        $this->updateUserSettingService = $updateUserSettingService;
    }

    #[Route('/bank', name: 'app_bank_account_index', methods: ['GET'])]
    public function index(BankAccountRepository $bankAccountRepository, UpdateUserSettingService $updateUserSettingService): Response
    {
        $banks = $bankAccountRepository->findByUser($this->getUser());

        $this->updateUserSettingService->update('Bank', $bankAccountRepository);

        return $this->render('bank_account/index.html.twig', [
            'bank_accounts' => $banks,
        ]);
    }

    #[Route('/bank/new', name: 'app_bank_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BankAccountRepository $bankAccountRepository): Response
    {
        $bankAccount = new BankAccount();
        $form = $this->createForm(BankAccountType::class, $bankAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bankAccount->setUser($this->getUser());

            $bankAccountRepository->add($bankAccount, true);


            return $this->redirectToRoute('app_bank_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bank_account/new.html.twig', [
            'bank_account' => $bankAccount,
            'form' => $form,
        ]);
    }

    #[Route('/bank/{id}', name: 'app_bank_account_show', methods: ['GET'])]
    public function show(BankAccount $bankAccount): Response
    {
        return $this->render('bank_account/show.html.twig', [
            'bank_account' => $bankAccount,
        ]);
    }

    #[Route('/bank/{id}/edit', name: 'app_bank_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BankAccount $bankAccount, BankAccountRepository $bankAccountRepository): Response
    {
        $form = $this->createForm(BankAccountType::class, $bankAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bankAccountRepository->add($bankAccount, true);

            return $this->redirectToRoute('app_bank_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bank_account/edit.html.twig', [
            'bank_account' => $bankAccount,
            'form' => $form,
        ]);
    }

    #[Route('/bank/{id}', name: 'app_bank_account_delete', methods: ['POST'])]
    public function delete(Request $request, BankAccount $bankAccount, BankAccountRepository $bankAccountRepository, UpdateUserSettingService $updateUserSettingService): Response
    {
        if ($this->isCsrfTokenValid('delete' . $bankAccount->getId(), $request->request->get('_token'))) {
            $bankAccountRepository->remove($bankAccount, true);

            $this->updateUserSettingService->update('Bank', $bankAccountRepository);
        }

        return $this->redirectToRoute('app_bank_account_index', [], Response::HTTP_SEE_OTHER);
    }
}
