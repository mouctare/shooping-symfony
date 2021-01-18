<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\CentimesTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class PriceType extends AbstractType 
{
  
    public function buildForm(FormBuilderInterface $builder, array $options)
     {
            $builder->addModelTransformer(new CentimesTransformer);
     }
        public function getParent()
        {
            return NumberType::class;
        }
      
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'divide' => true
        ]);
    }

}