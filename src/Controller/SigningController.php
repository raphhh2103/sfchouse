<?php

namespace App\Controller;

use App\Entity\JobsSfc;
use App\Entity\Results;
use App\Entity\Signing;
use App\Entity\UserJobs;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use function Sodium\add;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SigningController extends Controller
{
    /**
     * @Route("/result/signing", name="result_signing")
     *
     */
    public function index()
    {
        $id = 0;
        $repo1 = $this->getDoctrine()->getRepository('App:UserJobs')->findAll();
        $userJobs = $repo1;
        $repo = $this->getDoctrine()->getRepository('App:JobsSfc')->findAll();
        $jobsfc = $repo;
        foreach ($jobsfc as $key => $value) {
//            dump($value);
            foreach ($userJobs as $k => $v) {
                dump('0');
                if ($v->getFormateur() == $this->getUser()) {
                    dump('1');

                    $j = $value->getUser();
                    $u = $value;
//                dump($value);

                    if ($j->getId() === $this->getUser()->getId()) {
                        dump('2');
                        $signing = new Signing();
                        $signing->setUserJobs($v);
                        $signing->setDate(new \DateTime());
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($signing);
                        $em->flush();
                        $result = new Results();
                        dump($u);
                        $result->setJobSfc($u);
                        $result->setValue(4);
                        $result->setSigning($signing);
                        $ems = $this->getDoctrine()->getManager();
                        $ems->persist($result);
                        $ems->flush();
                        dump($result);
                        $id = $result->getId();
                        return $this->redirectToRoute('result_form_graphic', array('id' => $id));
                    } else {
                        return $this->redirectToRoute('home');
                    }
                }
            }
        }
//        dump($jobsfc);
//        dump($userJobs);
//        dump($jobsfc->getUserId()); die();


        // replace this line with your own code!
        return $this->render('result/singning.html.twig');
    }

    /**
     * @Route("result/update/{id}",name="result_form_graphic")
     * @param  {id}
     * @param Request $request
     * @return Response
     */
    public function updateResultAction(Request $request, Results $result)
    {

        $form = $this->createFormBuilder($result)
            ->add('value', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'save'))
            ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($result);
            $em->flush();
            $id = $result->getId();
            return $this->redirectToRoute('result_graphic', array('id' => $id));
        }


        return $this->render('result/formGraphic.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("result/graphic/{id}", name = "result_graphic")
     * @param {id}
     * @return Response
     */
    public function ResultForGraphicalAction(Results $result)
    {
        $currentJobSfc = null;
        $currentJobSfc1 = null;
        $currentJobSfc2 = null;
        $currentJobSfc3 = null;
        $currentJobSfc4 = null;
        $currentJobSfc5 = null;
        $value = $result->getValue();
        $user = $this->getUser();
        $compt = 0;
        $jobSfc = $this->getDoctrine()->getRepository('App:JobsSfc')->findAll();
//        dump($jobSfc);
        foreach ($jobSfc as $key => $values) {
//            dump($values);
            if ($values->getUser() === $user) {
                if ($key === 0) {
                    $currentJobSfc[0] = $values;
//                dump($currentJobSfc);
                    dump('0');
                }
                if ($key === 1) {
                    $currentJobSfc[1] = $values;
//                    dump($currentJobSfc1);
                    dump('1');
                }
                if ($key === 2) {
                    $currentJobSfc[2] = $values;

                    dump('2');
                }
                if ($key === 3) {
                    $currentJobSfc[3] = $values;
                    dump($currentJobSfc);

                }
                if ($key === 4) {
                    $currentJobSfc[4] = $values;
                    dump('4');

                }
                if ($key === 5) {
                    $currentJobSfc[5] = $values;
                    dump('5');

                }


                return $this->render('result/graphic.html.twig', array(
                    'value' => $value,
                    'sfc'=>$currentJobSfc,
                    'jobs'=>$currentJobSfc[0]->getJobs(),
                ));
            }
        }


        return $this->render('result/graphic.html.twig', array(
            'value' => $value,
            'sfc'=>$currentJobSfc,
            'jobs'=>$currentJobSfc[0]->getJobs(),
        ));
    }


}
