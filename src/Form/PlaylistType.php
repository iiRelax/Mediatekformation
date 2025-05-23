<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace App\Form;
use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
/**
 * Description of PlaylistType
 *
 * @author zmuku
 */
class PlaylistType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('name')
                ->add('description')
                ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
                ]);
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}