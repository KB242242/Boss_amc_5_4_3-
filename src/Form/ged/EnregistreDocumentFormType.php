<?php

namespace App\Form\ged;

use App\Entity\ged\GedDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EnregistreDocumentFormType extends AbstractType
{
    private $langue=[];
    private $listeChoix=[];
	// Le tableau langue = 
    public function createEnregistreDocumentFormType(array $langue, array $listeChoix)    
    {
        $this->listeChoix = $listeChoix;
        $this->langue = $langue;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',TextType::class, ['required'=> true, 'help'=>  $this->langue['pageFormulaire']['frm_help_not_1_0']])
            ->add('extension',TextType::class, ['required'=> true, 'help'=>  $this->langue['pageFormulaire']['frm_help_not_1_0']])

            ->add('gisement',ChoiceType::class, ['required'=> true,'choices' => $this->listeChoix['listeChoixGisement']])
			->add('dossier', HiddenType::class, [
                'mapped' => false, // Indique que le champ n'est pas mappé à une propriété de l'entité
                'data' => '', // Valeur par défaut du champ
            ])

 
            ->add('version',TextType::class, ['required'=> true, 'help'=>  $this->langue['pageFormulaire']['frm_help_not_1_0']])
            ->add('validite',TextType::class, ['required'=> true, 'help'=>  $this->langue['pageFormulaire']['frm_help_not_1_0']])
            ->add('motsCles',TextType::class, ['required'=> true, 'help'=>  $this->langue['pageFormulaire']['frm_help_not_1_0']])
            ->add('rem',TextType::class, ['required'=> true, 'help'=>  $this->langue['pageFormulaire']['frm_help_not_1_0']])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GedDocument::class,
        ]);
    }
}
