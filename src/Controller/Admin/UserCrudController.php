<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;


class UserCrudController extends AbstractCrudController
{
    public const ACTION_SAVE_CSV = "SAVE_CSV";

    // entity manager
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }


    public static function getEntityFqcn(): string
    {
        return User::class;
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
            })
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->add(Crud::PAGE_DETAIL, Action::new('saveCsv','Save as Csv', 'fa fa-save-as-csv')
            ->displayAsButton()
            ->linkToCrudAction('saveUsersToCsv'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            ArrayField::new('roles'),
            TextField::new('password')->hideOnIndex()
        ];
    }


    public function saveUsersToCsv(
        AdminContext $context,
        AdminUrlGenerator $adminUrlGenerator,
        EntityManagerInterface $em
    ): Response {

        $userRepo = $this->em->getRepository(User::class);
        $users= $userRepo->findAll(); // Doctrine query
        $rows = array();
        $columns = array(
            'id',
            'email',
        );
        $rows[] = implode(',',$columns);
        foreach($users as $user){
            $data = array(
                $user->getId(),
                $user->getEmail(),
            );
            $rows[] = implode(',',$data);
        }
        $content = implode("\n",$rows);
        $response = new Response($content);
        $response->headers->set("Content-Type",'text/csv');
        $response->headers->set("Content-Disposition",'attachment; filename="users.csv"');

        return $response;
    }
}