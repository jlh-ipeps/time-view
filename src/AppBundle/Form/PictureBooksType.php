<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PictureBooksType extends AbstractType {
    
  /**
   * {@inheritdoc}
   */
    public function buildForm(FormBuilderInterface $builder, array $options) {
      
      
      
        $builder
            ->add('pictures', CollectionType::class, array(
                'entry_type'   => PicturePicturesBookType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_options'  => array('userId' => $options['userId']),
            ))
        ;
  }
  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver) {
      $resolver->setDefaults(array(
          'data_class' => 'AppBundle\Entity\Picture',
          'userId' => ''
      ));
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix() {
      return 'appbundle_picture';
  }

}
