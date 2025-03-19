<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace App\Form;
use App\Entity\Playlist;
use App\Entity\Categorie;
use App\Entity\Formation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use DateTime;
/**
 * Description of FormationType
 *
 * @author zmuku
 */
class FormationType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('title')
                ->add('description')
                ->add('categories', EntityType::class, [
                    'class' => Categorie::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'required' => false
                ])
                ->add('playlist', EntityType::class, [
                    'class' => Playlist::class,
                    'choice_label' => 'name',
                    'multiple' => false,
                    'required' => true
                ])
                ->add('publishedAt', DateType::class, [
                'widget' => 'single_text',
                'data' => isset($options['data']) &&
                    $options['data']->getPublishedAt() != null ? $options['data']->getPublishedAt() : new DateTime('now'),
                'label' => 'Date'
                ])
                ->add('videoId')
                ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
                ]);
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}