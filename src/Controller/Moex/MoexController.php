<?php

namespace App\Controller\Moex;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Moex\FileFormType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Moex\FullOrderRepository;
use Symfony\Component\HttpFoundation\File\File;
use App\Extensions\Moex\ParseCsv;
use App\Entity\Moex\FullOrder;
use App\Extensions\Moex\UploadFile;

class MoexController extends AbstractController
{
    /**
     * @Route("/moex/", name="moex_index", methods={"GET"})
     */
    public function index(Request $request, FullOrderRepository $forep): Response
    {
        $form = $this->createForm(FileFormType::class);
        $limit = $this->getParameter('moex')['limit-in-page'];
        $data = $forep->getFullOrderPage($limit, $request);

        return $this->render('moex/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $data
        ]);
    }

    /**
     * @Route("/moex/load_data", name="moex_load_data", methods={"POST"})
     */
    public function loadData(Request $request, FullOrderRepository $forep): Response
    {
        $form = $this->createForm(FileFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['file']->getData();

            try {
                $directory = $this->getParameter('moex')["file-directory"];
                $filePath = (new UploadFile($file, $directory))->getFilePath();
                $data = (new ParseCsv($filePath))->getData();
            } catch (\Throwable $th) {
                $this->get('session')->getFlashBag()->add('moex-error', 'Ошибка при разборе файла!');
            }

            try {
                $forep->insertData($data);
                $this->get('session')->getFlashBag()->add('moex-complite', 'Данные загружены!');
            } catch (\Throwable $th) {
                $this->get('session')->getFlashBag()->add('moex-error', 'Ошибка при сохранении данных!');
            }
        }

        return $this->redirect($this->generateUrl('moex_index'));
    }
}
