<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Repository\DeckRepository;
use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JuegoController extends Controller
{
    /**
     * @Route("/play&u={id}", name="game_prepara")
     */
    public function preparaAction(Request $request,DeckRepository $dr, UserRepository $ur, User $user)
    {
        if (count($_REQUEST) == 0){
//            $this->addFlash('error', 'nadie ha lanzado el formulario');
        }else{
//            $this->addFlash('exito', 'formulario lanzado');
//            $oponente = $this->buscarOponenteAleatorio($user);
            $oponente = $this->buscarOponenteAleatorio($ur, $user);
            $cartas_oponente = $this->buscar4CartasRnd($oponente);
            for ($i=0;$i< count($cartas_oponente); $i++) {
                $this->addFlash('exito', $oponente->getNickname() .':' . $cartas_oponente[$i]);
            }
            $miEscuad = $request->get('deck_select');
            $miEscuad = $dr->unDeck($miEscuad)[0];
            $misCartas = $miEscuad->getCardsContained();

//            $this->addFlash('exito', 'mazo:'.$miEscuad[0]->getDeckName());

//            $miEscuad = $dr->findOneBy(['id'=> $miEscuad]);
            for ($i=0;$i< count($misCartas); $i++){
                $this->addFlash('exito', 'yo-c:'.$misCartas[$i]);

            }
        }
        return $this->render('juego/main.html.twig', [
            'usuario' => $user,
            'decks' => $user->getDecks()
        ]);
    }

    public function buscarOponenteAleatorio(UserRepository $userRepository, User $usuario){
        $oponentes = $userRepository->buscarTodosMenosLogeado($usuario);
//        $posibles_id  = [];
//        foreach ($oponentes as $oponente){
//            }
        $oponente_rnd = 14;
        $oponente = $userRepository->find($oponente_rnd);
        return $oponente;
    }

    //le doy un User y me devuelve un array con 4 cartas aleatorias (pueden repetirse)
    public function buscar4CartasRnd(User $oponente){
        $cartas = $oponente->getCards();
        $cartas_enemigo = [];
        for ($i = 0; count($cartas_enemigo)<4;$i++){
            $cartaRnd = $cartas[mt_rand(1,count($cartas))];
            if ($cartaRnd <> ''){
                array_push($cartas_enemigo, $cartaRnd);
            }
        }
        return $cartas_enemigo;

    }

}
