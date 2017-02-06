<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\HttpFoundation\File\File;

class ImageType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('file', FileType::class,array(
               'constraints' => array(
                    new Image( array(
                        'minWidth' => 400,
                        'minHeight' => 400,
                        'mimeTypes'=> array(
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                            'image/gif',
                        ),
                    )),
//                    new File( array('maxSize' => 100000)),
                    )
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
/*
          'attr' => [
              'id' => 'dropzone_form',
              'class' => 'dropzone',
            ]
 */
          'data_class' => 'AppBundle\Entity\File',
      ));
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix() {
      return 'appbundle_image';
  }


}
