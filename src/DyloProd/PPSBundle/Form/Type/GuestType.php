<?Php 

namespace DyloProd\PPSBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestType extends AbstractType
{
    private $labels;
    private $name;
    
    public function __construct(array $set)
    {
        $this->labels = $set["labels"];
        $this->name = $set["name"];
        
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                "label" => $this->labels[array_rand($this->labels)] 
           ));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DyloProd\PPSBundle\Entity\Guest',
        ));
    }
    
    public function getName()
    {
        return $this->name;
    }
}