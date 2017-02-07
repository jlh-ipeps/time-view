<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookTitleType extends AbstractType {

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
      $builder
              ->add('Title', TextType::class, array(
                "label" => false,
                'required' => false,
            ))
            ->add('submit', SubmitType::class, array(
                "label"=>"form.submit",
            ))
        ;
  }
  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver) {
      $resolver->setDefaults(array(
          'data_class' => 'AppBundle\Entity\Book',
      ));
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix() {
      return 'appbundle_book_title';
  }

}
