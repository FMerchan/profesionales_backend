<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DownloadController extends AbstractController
{
    /**
     * @Route("/download/{file}", name="download_file")
     */
    public function download($file)
    {
        // Define la ruta completa al archivo que deseas descargar
        $filePath = $this->getParameter('kernel.project_dir') . '/templates/app/' . $file;

        // Verifica si el archivo existe
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('El archivo no existe');
        }

        // Crea una respuesta binaria para enviar el archivo al cliente
        $response = new BinaryFileResponse($filePath);

        // Define el encabezado de la respuesta para que el navegador inicie la descarga
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file
        );

        return $response;
    }
}