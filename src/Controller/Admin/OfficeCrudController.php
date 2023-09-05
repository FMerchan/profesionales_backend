<?php

namespace App\Controller\Admin;

use App\Entity\Office;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class OfficeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Office::class;
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
        return [
            AssociationField::new('userProfessional'),
            TextField::new('name'),
            TextEditorField::new('detail')->hideOnIndex(),
            TextField::new('address'),
            //AssociationField::new('city'),
            //AssociationField::new('state'),
            //AssociationField::new('country'),
            TextField::new('postalCode')->hideOnIndex(),
            NumberField::new('longitude')->hideOnIndex(),
            NumberField::new('latitude')->hideOnIndex(),
            NumberField::new('price'),
            TextField::new('currency')->hideOnIndex(),
            TextField::new('formattedBusinessDays', 'Business Days')
                ->onlyOnDetail() ,
            NumberField::new('duration')->hideOnIndex(),
            TextField::new('formattedAvailableTimes', 'Available Times')
                ->onlyOnDetail() ,
        ];
    }
}