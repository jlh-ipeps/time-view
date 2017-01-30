<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PicturePicturesBookType extends AbstractType {
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $this->userId = $options['userId'];
      
        $builder
            ->add('book', EntityType::class, array(
                // query choices from this entity
                'class' => 'AppBundle:Book',
                'query_builder' => function ($bookRepo) {
                    return $bookRepo->findBooksByUser($this->userId);
                },
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => false,
            ))
        ;
    }
  
  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver) {
      $resolver->setDefaults(array(
          'data_class' => 'AppBundle\Entity\Picture',
          'userId'         => NULL
      ));
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix() {
      return 'appbundle_picture';
  }

}
