<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PictureType extends AbstractType {

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('route', TextType::class, array(
                    'required'    => false,
                    'attr' => array(
                        'placeholder' => 'route'
                    ),
                    'empty_data'  => null
                )
            )
            ->add('postalCode', TextType::class, array(
                    'required'    => false,
                    'attr' => array(
                        'placeholder' => 'code postal'
                    ),
                    'empty_data'  => null
                )
            )
            ->add('locality', TextType::class, array(
//                    'required'    => false,
                    'attr' => array(
                        'placeholder' => 'locality'
                    ),
                    'empty_data'  => null
                )
            )
            ->add('country', TextType::class, array(
//                    'required'    => false,
                    'attr' => array(
                        'placeholder' => 'country'
                    ),
                    'empty_data'  => null
                )
            )
            ->add('lat', HiddenType::class, array(
                    'required'    => false,
                    'empty_data'  => null
                )
            )
            ->add('lng', HiddenType::class, array(
                    'required'    => false,
                    'empty_data'  => null
                )
            )
            ->add('submit', SubmitType::class)
        ;
  }
  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver) {
      $resolver->setDefaults(array(
          'data_class' => 'AppBundle\Entity\Picture',
          'attr' => ['id' => 'geoform']
      ));
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix() {
      return 'appbundle_picture';
  }

}
