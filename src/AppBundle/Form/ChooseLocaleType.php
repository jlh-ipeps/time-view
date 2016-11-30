<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ChooseLocaleType extends AbstractType {

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {

    $data = $options['data'];
    //$languages = $this->getParameter('languages');
    
    $builder
        ->add('locale', ChoiceType::class, 
            array(
      //          'choices' => $languages,
//                    array(
//                        'English' => 'en', 
//                        'Deutsch' => 'de', 
//                        'Fançais' => 'fr', 
//                        'Nederlands' => 'nl',
//                    ),
                'expanded' => true,
                'multiple' => false,
                'required'  => true,
                'data' => $data['locale'],
                
            )
        )
        ->setMethod('POST')
        ;
    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(
            array(
                'attr' => [
                    'class' => 'sidebar_form',
                ]
            )
        );
    }

}
