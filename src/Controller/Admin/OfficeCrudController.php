<?php

namespace App\Controller\Admin;

use App\Entity\Office;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\RequestStack;

class OfficeCrudController extends AbstractCrudController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getEntityFqcn(): string
    {
        return Office::class;
    }

    public function configureAssets(Assets $assets): Assets
    {
        // Obtén el contexto actual para verificar la página actual.
        $request = $this->requestStack->getMasterRequest();

        // Verifica si la página actual es la de detalle (detail).
        if ($request->attributes->get('_route') === 'admin' && $request->query->get('crudAction') === 'detail') {
            // Agrega el archivo JS solo en la página de detalle.
            $assets->addJsFile('easyAdmin/office/detail.js');
            $assets->addCssFile('easyAdmin/office/detail.css');
            $assets->addJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js');
            $assets->addCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
            $assets->addJsFile('https://code.jquery.com/jquery-3.6.0.min.js');
        }

        return parent::configureAssets($assets);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Consultorio')
            ->setEntityLabelInPlural('Consultorios')
            ->setPageTitle(Crud::PAGE_INDEX, 'Consultorio')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $entity = $this->getContext()->getEntity();

        $fields = [
            AssociationField::new('userProfessional'),
            TextField::new('name'),
            TextEditorField::new('detail')->hideOnIndex(),
            TextField::new('address')->setCssClass("field-number data-address"),
            TextField::new('postalCode')->hideOnIndex(),
            NumberField::new('longitude')->hideOnIndex()->setCssClass("field-number data-longitude"),
            NumberField::new('latitude')->hideOnIndex()->setCssClass("field-number data-latitude"),
            NumberField::new('price'),
            TextField::new('currency')->hideOnIndex(),
            TextField::new('formattedBusinessDays', 'Business Days')
                ->onlyOnDetail() ,
            NumberField::new('duration')->hideOnIndex(),
            TextField::new('formattedAvailableTimes', 'Available Times')
                ->onlyOnDetail() ,
        ];


        // ->setTemplateName('office/detail.html.twig')

        return $fields;
    }
}