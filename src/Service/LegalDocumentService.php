<?php

namespace App\Service;

use App\Entity\LegalDocument;
use Doctrine\ORM\EntityManagerInterface;

class LegalDocumentService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveDocument(LegalDocument $document)
    {
        if ($document->getIsActive()) {
            $document->setValidFrom(new \DateTime());
            $this->deactivateOtherDocuments($document);
        }

        // Persiste y guarda la entidad
        $this->entityManager->persist($document);
        $this->entityManager->flush();

        // Retorna el documento guardado u otra respuesta si lo deseas
        return $document;
    }

    // Función para desactivar otros documentos del mismo tipo
    private function deactivateOtherDocuments(LegalDocument $entity)
    {
        $otherDocuments = $this->entityManager->getRepository(LegalDocument::class)->findBy([
            'type' => $entity->getType(),
            'isActive' => true,
        ]);

        foreach ($otherDocuments as $otherDocument) {
            $otherDocument->setValidUntil(new \DateTime());
            $otherDocument->setIsActive(false);
        }
    }
}