<?php

namespace Plemi\Twig\Extensions;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Base64Extension class
 *
 * @author David Guyon <dguyon@gmail.com>
 * @link http://yoann.aparici.fr/post/18599782775/extension-twig-pour-encoder-les-images-en-base-64
 */
class Base64Extension extends \Twig_Extension
{
    private $webDir;

    /**
     * Constructor
     * Inject path to web directory
     *
     * @param string $webDir
     */
    public function __construct($webDir)
    {
        $this->webDir = $webDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'image64' => new \Twig_Function_Method($this, 'image64'),
        );
    }

    /**
     * Return a base 64 encoded string only from image content type
     *
     * @param  string $path Path to image
     * @return string
     */
    public function image64($path)
    {
        $fullPath = $this->webDir.$path;
        $file = new File($fullPath, true);

        if (!$file->isFile() || 0 !== strpos($file->getMimeType(), 'image/')) {
            return;
        }

        $binary = file_get_contents($fullPath);

        return sprintf('data:image/%s;base64,%s', $file->guessExtension(), base64_encode($binary));
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'plemi_base64';
    }
}