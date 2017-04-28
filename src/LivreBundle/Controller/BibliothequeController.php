<?php

namespace LivreBundle\Controller;

use LivreBundle\Entity\Livre;
use LivreBundle\Form\ListeLivreType;
use LivreBundle\Form\LivreType;
use LivreBundle\Form\RechercheISBNLivreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BibliothequeController extends Controller
{
    /**
     * Liste les  utilisateurs
     * @param Request $request
     * @return Response
     */
    public function listeAction(Request $request)
    {
        //TODO : notion d'un lieu
        $this->securite();
        // Formulaire pour modifier les dernières entrées de la bibliotheque
        //$formBibliotheque = $this->createForm(ListeLivreType::class, $this->getUser());
        $formBibliotheque = array();
        foreach ($this->getUser()->getListeLivres() as $livre)
            $formBibliotheque[] = $this->createForm(LivreType::class, $livre)->createView();
        // Formulaire pour ajouter un livre
        $formAjout = $this->createForm(RechercheISBNLivreType::class)
            ->add('btnAjouterLivre', ButtonType::class, array(
                'label' => 'Ajouter >>',
            ));

        return $this->render('LivreBundle:Bibliotheque:liste.html.twig', array(
            'form_bibliotheque' => $formBibliotheque,
            'form_ajout'        => $formAjout->createView()
        ));
    }

    /**
     * Ajoute un livre à la bibliotheque de l'utilisateur courant
     * @param Request $request
     * @return JsonResponse
     */
    public function ajoutAjaxAction(Request $request)
    {
        $this->securite();
        $formAjout = $this->createForm(RechercheISBNLivreType::class)
            ->add('btnAjouterLivre', ButtonType::class, array(
                'label' => 'Ajouter >>',
            ));
        $formAjout->handleRequest($request);
        $listeErreurs = array();
        $livre        = null;

        if ($formAjout->isSubmitted() && $formAjout->isValid()) {
            $isbn = $formAjout->get('isbn')->getData();
            // On recherche si le livre est en base
            $baseLivre = $this->getDoctrine()->getRepository('LivreBundle:BaseLivre')->findOneByIsbn($isbn);
            // S'il n'existe pas, on appelle google
            if (true === is_null($baseLivre)) {
                // On demande à google
                $baseLivre = $this->get('livre.google_get_livre_service')->rechercheLivreParISBN($isbn);
            }
            // On a trouvé un livre... Youhou \o/
            $em = $this->getDoctrine()->getManager();
            if (false === is_null($baseLivre)) {
                // mais est-ce que l'utilisateur courant l'a-t-il déjà ?
                if (false === $em->getRepository('LivreBundle:Livre')->utilisateurPossedeLivre($baseLivre, $this->getUser())) {
                    $livre = new Livre();
                    $livre->setAction('ajout')
                        ->setDateAction(new \DateTime())
                        ->setDateAjout(new \DateTime())
                        ->setPrix($baseLivre->getPrix())
                        ->setBaseLivre($baseLivre)
                        ->setProprietaire($this->getUser());

                    $em->persist($livre);
                    $em->flush();
                } else {
                    $listeErreurs[] = "Vous possédez déjà ce livre, petit coquinou !";
                }
            } else {
                $listeErreurs[] = "Le livre avec l'ISBN " . $isbn . " n'a psa été trouvé par google. ";
            }

        } else {
            // Affichage de tous les erreurs
            foreach ($formAjout->getErrors(true, true) as $erreur) {
                $listeErreurs[] = $erreur->getMessage();
            }
        }
        // Code de retours
        $contentHtml = '';
        $codeRetour  = '';
        // Création du nouveau formulaire
        $formLivre = null;
        if (false === is_null($livre) && count($listeErreurs) === 0) {
            // Succes
            $formLivre  = $this->createForm(LivreType::class, $livre);
            $codeRetour = '200';
            $content    = $this->renderView('LivreBundle:Block:bibliotheque_ajout_livre.html.twig', array(
                'form_livre' => $formLivre->createView(),
            ));
        } else {
            // Dommage, on a des problemes
            $codeRetour = '500';
            $content    = implode('<br/>', $listeErreurs);
        }
        // On retourne le tout !
        return new JsonResponse(
            array(
                'code' => $codeRetour,
                'html' => $content
            )
        );
    }

    /**
     * Modiei un livre l'utilisateur courant
     * @param Request $request
     * @return Response
     */
    public function modifieAjaxAction(Request $request, Livre $livre)
    {
        $this->securite();
        $listeErreurs = array();
        $em           = $this->getDoctrine()->getManager();
        // Gestion du formulaire
        $formModifie = $this->createForm(LivreType::class, $livre);
        $formModifie->handleRequest($request);
        if ($formModifie->isSubmitted() && $formModifie->isValid()) {
            $em->persist($livre);
            $em->flush();
        } else {
            foreach ($formModifie->getErrors(true, true) as $erreur) {
                $listeErreurs[] = $erreur->getMessage();
            }
        }

        // On retourne le tout !
        return $this->render('LivreBundle:Block:bibliotheque_ajout_livre.html.twig', array(
            'form_livre' => $formModifie->createView(),
        ));
    }

    /**
     * Supprime un livre
     * @param Request $request
     * @param Livre $livre
     * @return JsonResponse
     */
    public function supprimeAjaxAction(Request $request, Livre $livre)
    {
        $this->securite();
        $this->getDoctrine()->getManager()->remove($livre);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(array(
            'code' => 200
        ));
    }

    /**
     * Gère la sécurité de la page
     * @param Livre $livre
     * @return bool
     */
    protected function securite(Livre $livre = null){
        // Vérifie si l'utilisateur est connecté
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        // Vérifie que le livre lui appartient bien
        $user = $this->getUser();
        if(false === is_null($livre) && $livre->getProprietaire()->getId() !== $user->getId()){
            throw $this->createAccessDeniedException();
        }
        return true;
    }

}
