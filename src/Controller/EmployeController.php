<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/*
* VOUS DEVEZ IMPORTEZ TOUTES LES CLASS QUE VOUS UTILISEREZ 
*/

class EmployeController extends AbstractController
{
    /**
     * une fonction d'un Controller s'appellera une action.
     * Le nom de cette action (cette fonction) commencera TOUJOURS par un verbe.
     * On privilègerie l'anglais. A défaut, on nomme correctement ses variables en francais.
     * 
     * 
     * La route = 1param: l'uri, 2param: le nom de la route, 3param: la méthode HTTP.
     * @Route("/ajouter-un-employe.html", name="employe_create", methods={"GET|POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {

        ////////////////// ------------- 1ere Partie : GET ----------- ///////////
        # Variabilisation d'un nouvel objet de type Employe
        $employe = new Employe();

        # On créé dans une variable un formulaire à partir de notre prototype EmployeFormType.
        # Pour faire fonctionner le mécanisme d'auto hydratation d'objet de symfony, vous devrez passez en 2eme argument votre objet $employe.
        # Mais également que tous les noms de vos champs le prototype de form (EmployeFormType) aient EXACTEMENT les mémes noms que les propriétés de la Class à laquelle il est rattaché.
        $form = $this->createForm(EmployeFormType::class, $employe);

        # Pour que Symfony récupére les données des inputs du from, vous devez handleRequest().
        $form->handleRequest($request);


        //////////// -------------- 2eme Partie : POST ------------ ////////////////
        if ($form->isSubmitted() && $form->isValid()) {
            
            # Cette méthode pour récupérer les données des inputs est la premiéres méthode.
            # Nous utiliserons la seconde, grace au mécanisme d'auto hydratation de Symfony.
            // $form->get('salary')->getData();
            
            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('default_home');
        }


        /////////// -----------   1ere Partie : GET       --------------- ///////////////
        # On passe en parémetre le formulaire a notre vue Twig.
        return $this->render("form/employe.html.twig", [
            "form_employe" => $form->createView() # On doit createView() sur $form
        ]);
    } # end fucntion create()


    /*
    * @Route("/modifier-un-employe-{id}", name="employe_update", methods={"GET|POST"})
    */
public function update(Employe $employe, Request $request, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(EmployeFormType::class, $employe)
        ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('default_home');
        } // end if()

        return $this->render("form/employe.html.twig",[
            'employe' => $employe,
            'form_employe' => $form->createView()
        ]);
}


} # end class
