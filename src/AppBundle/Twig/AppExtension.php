<?php
// http://stackoverflow.com/questions/7704253/how-to-concatenate-strings-in-twig
// ~ tilde doen not work with trans
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('concat', [$this, 'concat'], ['is_safe' => ['html']]),
        ];
    }

    public function concat()
    {
        return implode('', func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_extension';
    }
}