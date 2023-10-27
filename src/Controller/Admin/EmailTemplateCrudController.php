<?php

namespace App\Controller\Admin;

use App\Entity\EmailTemplate;
use App\Entity\LegalDocument;
use App\Service\LegalDocumentService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EmailTemplateCrudController extends AbstractCrudController
{

    public function __construct() {
    }

    public static function getEntityFqcn(): string
    {
        return EmailTemplate::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Email Template')
            ->setEntityLabelInPlural('Emails Templates')
            ->setPageTitle(Crud::PAGE_INDEX, 'Emails Templates')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            Field::new('id')->onlyOnIndex()->hideOnForm(),
            TextField::new('name'),
            TextField::new('subject'),
            TextareaField::new('body')
                ->setFormTypeOption('attr', ['class' => 'ckeditor'])
                ->hideOnIndex(),
            TextField::new('type')->onlyOnIndex(),
        ];

        return $fields;
    }
}