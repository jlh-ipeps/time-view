<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PictureType extends AbstractType {

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
      
        $builder
            ->add('route', TextType::class, array(
                    "label"=>"form.route",
                    'required'    => false,
                    'attr' => array(
                        'placeholder' => 'form.route',
                        'class' => 'firstUpper'

                    ),
                    'empty_data'  => null
                )
            )
            ->add('postalCode', TextType::class, array(
                    "label"=>"form.postalCode",
                    'required'    => false,
                    'attr' => array(
                        'placeholder' => 'form.postalCode'
                    ),
                    'empty_data'  => null
                )
            )
            ->add('locality', TextType::class, array(
                    "label"=>"form.locality",
                    'required'    => false,
                    'attr' => array(
                        'placeholder' => 'form.locality',
                        'class' => 'firstUpper'
                    ),
                    'empty_data'  => null
                )
            )
            ->add('country', CountryType::class, array(
                    "label"=>"form.country",
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
            ->add('submit', SubmitType::class, array(
                    "label"=>"form.submit",
                )
            )
            ->add('clear', ButtonType::class, array(
                    "label"=>"form.clear",
                )
            )
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
