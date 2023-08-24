<?php

namespace App\Controller\Admin;


namespace App\Controller\Admin;

use App\Entity\UserProfessional;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class UserProfessionalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserProfessional::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('first_name'),
            TextField::new('last_name'),
            AssociationField::new('user', 'User')
                ->autocomplete()
                ->setLabel('Email')
                ->formatValue(function ($id, $userProfessional) {
                    $user = $userProfessional->getUser();
                    return $user ? $user->getEmail() : 'No user associated';
                }),
            TextField::new('license_number')
        ];

        // Agrega el campo de Professions al formulario de creación
        if ($pageName === Crud::PAGE_NEW) {
            $fields[] = ArrayField::new('userProfessionalProfessionals', 'Professions')
                ->formatValue(function ($value, $entity) {
                    return $entity->getProfessionsNames();
                });
        }

        // Agrega el campo de Professions como un campo de texto en el listado
        if ($pageName === Crud::PAGE_INDEX) {
            $fields[] = ArrayField::new('userProfessionalProfessionals', 'Professions')
                ->formatValue(function ($value, $entity) {
                    return $entity->getProfessionsNames();
                });
        }

        return $fields;
    }
}