<?php

namespace App\Controller\Admin;

use App\Entity\LegalDocument;
use App\Service\LegalDocumentService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LegalDocumentCrudController extends AbstractCrudController
{

    private LegalDocumentService $legalDocumentService;

    public function __construct(LegalDocumentService $legalDocumentService) {
        $this->legalDocumentService = $legalDocumentService;
    }

    public static function getEntityFqcn(): string
    {
        return LegalDocument::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Documento Legal')
            ->setEntityLabelInPlural('Documentos Legales')
            ->setPageTitle(Crud::PAGE_INDEX, 'Documentos legales')
            ->setDefaultSort(['id' => 'DESC'])
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            Field::new('id')->onlyOnIndex()->hideOnForm(),
            ChoiceField::new('type')->setChoices([
                'Términos y Condiciones' => LegalDocument::TERMS_AND_CONDITIONS,
                'Política de Privacidad' => LegalDocument::PRIVACY_POLICY,
            ])->allowMultipleChoices(false),
            BooleanField::new('isActive'),
            TextareaField::new('content')
                ->hideOnIndex()
                ->setFormType(CKEditorType::class)
                ->setFormTypeOptions([
                    'config' => [
                        'removeButtons' => 'Save,NewPage,ExportPdf,Find',
                    ],
                ]),
            DateField::new('validFrom')->setFormat('yyyy-MM-dd')->onlyOnIndex(),
            DateField::new('validUntil')->setFormat('yyyy-MM-dd')->onlyOnIndex(),
        ];

        return $fields;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Tu lógica personalizada para guardar aquí
        $this->legalDocumentService->saveDocument($entityInstance);
    }
}