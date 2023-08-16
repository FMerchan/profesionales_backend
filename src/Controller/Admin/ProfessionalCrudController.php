<?php

namespace App\Controller\Admin;

use App\Entity\Professional;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class ProfessionalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Professional::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, ACTION::NEW, function (Action $action) {
                return $action->setIcon('fa fa-user')->addCssClass('btn btn-success');
            })
            ->update(Crud::PAGE_INDEX, ACTION::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit')->addCssClass('btn btn-warning');
            });
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('description'),
            BooleanField::new('hasSpecification')->hideOnIndex()
        ];
    }
}