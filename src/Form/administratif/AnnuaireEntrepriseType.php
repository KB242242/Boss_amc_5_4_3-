<?php

namespace App\Form\administratif;

use App\Entity\administratif\AnnuaireEntreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class AnnuaireEntrepriseType extends AbstractType
{
    private $langue=[];
    private $listeChoix=[];

    public function createAnnuaireEntrepriseType(array $langue, array $listeChoix)    
    {
        $this->listeChoix = $listeChoix;
        $this->langue = $langue;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id')
            ->add('nom',TextType::class, ['required'=> true, 'help'=>  $this->langue['pageFormulaire']['frm_help_not_1_0']])
            ->add('ville',TextType::class, ['required'=> true, 'help'=> $this->langue['pageFormulaire']['frm_help_not_2_0']])
            ->add('pays',ChoiceType::class, ['required'=> true,'choices' => $this->listeChoix['listeChoixPays']])
            ->add('addPostale',TextareaType::class, ['required'=> false, 'help'=> $this->langue['pageFormulaire']['frm_help_not_4_0']])
            ->add('siteInternet',TextType::class, ['required'=> false])
            ->add('statusEntreprise',TextType::class, ['required'=> false])
            ->add('uidNom',ChoiceType::class, ['choices' => $this->listeChoix['listeChoixUid'],'required'=> false])
            ->add('uidVal',TextType::class, ['required'=> false])
            ->add('activite',TextareaType::class, ['required'=> false])
            ->add('clientFourniss',ChoiceType::class, ['choices' => $this->listeChoix['listeChoixLiensCommerciaux'],'required'=> false])
            ->add('rem',TextareaType::class, ['required'=> false]) 

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnnuaireEntreprise::class,
        ]);
    }
}
