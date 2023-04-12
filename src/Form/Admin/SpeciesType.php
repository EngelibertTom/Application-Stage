<?php

namespace App\Form\Admin;

use App\Entity\LeafType;
use App\Entity\Species;
use App\Entity\StatusUicn;
use App\Manager\AcidityManager;
use App\Manager\ExposureManager;
use App\Manager\FertilizerManager;
use App\Manager\MonthManager;
use App\Manager\SoilMoistureManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpeciesType extends AbstractType
{
    private $soilMoistureManager;
    private $acidityManager;
    private $exposureManager;
    private $fertilizerManager;
    private $monthManager;

    public function __construct(SoilMoistureManager $soilMoistureManager, AcidityManager $acidityManager,
                                ExposureManager $exposureManager, FertilizerManager $fertilizerManager, MonthManager $monthManager)
    {
        $this->soilMoistureManager = $soilMoistureManager;
        $this->acidityManager = $acidityManager;
        $this->exposureManager = $exposureManager;
        $this->fertilizerManager = $fertilizerManager;
        $this->monthManager = $monthManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom commun',
                'attr' => ['autocomplete' => 'off']
            ])
            ->add('latinName', TextType::class, [
                'attr' => ['autocomplete' => 'off']
            ])
            ->add('recommendedAcidityMin', ChoiceType::class, [
                'label' => 'Ph min',
                'choices' => $this->acidityManager->getList(true),
                'required' => false
            ])
            ->add('recommendedAcidityMax', ChoiceType::class, [
                'label' => 'Ph max',
                'choices' => $this->acidityManager->getList(true),
                'required' => false
            ])
            ->add('leafType', EntityType::class, [
                'class' => LeafType::class,
                'label' => 'Type de feuille'
            ])
            ->add('statusUicn', EntityType::class, [
                'class' => StatusUicn::class,
                'label' => 'Statut UICN',
                'required' => false
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Species::class,
        ]);
    }
}
