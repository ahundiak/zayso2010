<?php

namespace Zayso\ZaysoBundle\Component\HttpFoundation;

use Symfony\Component\HttpFoundation\SessionStorage\SessionStorageInterface;

class MySession extends \Symfony\Component\HttpFoundation\Session
{
  protected $storage;
  protected $started;
  
  protected $attributes;
  protected $flashes;
  protected $oldFlashes;
  protected $locale;
  protected $defaultLocale;

  public function __construct(SessionStorageInterface $storage, $defaultLocale = 'en')
  {
    $this->storage       = $storage;
    
    $this->flashes       = array();
    $this->oldFlashes    = array();
    $this->attributes    = array();
    
    $this->locale        = $defaultLocale;
    $this->defaultLocale = $defaultLocale;
    $this->setPhpDefaultLocale($this->defaultLocale);
    
    $this->started = false;
  }
  public function start()
  {
    if (true === $this->started) { return; }

    $this->storage->start();

    $this->started = true;
  }
  public function get($name, $default = null)
  {
    return $this->storage->read($name,$default);
  }
  public function set($name, $value)
  {
    if (false === $this->started) { $this->start(); }

    $this->storage->write($name,$value);
  }
  public function has($name)
  {
    die('session.has ' . $name);
    return array_key_exists($name, $this->attributes);
  }
  public function all()
  {
    if (1) return array();
    
    die('session.all'); // This gets called somewhere
    return $this->attributes;
  }

    /**
     * Sets attributes.
     *
     * @param array $attributes Attributes
     *
     * @api
     */
    public function replace(array $attributes)
    {
      die('session.replace');
        if (false === $this->started) {
            $this->start();
        }

        $this->attributes = $attributes;
    }

    /**
     * Removes an attribute.
     *
     * @param string $name
     *
     * @api
     */
    public function remove($name)
    {
      die('session.remove ' . $name);
        if (false === $this->started) {
            $this->start();
        }

        if (array_key_exists($name, $this->attributes)) {
            unset($this->attributes[$name]);
        }
    }

    /**
     * Clears all attributes.
     *
     * @api
     */
    public function clear()
    {
        if (false === $this->started) {
            $this->start();
        }

        $this->attributes = array();
        $this->flashes = array();
        $this->setPhpDefaultLocale($this->locale = $this->defaultLocale);
    }

    /**
     * Invalidates the current session.
     *
     * @api
     */
    public function invalidate()
    {
        $this->clear();
        $this->storage->regenerate();
    }

    /**
     * Migrates the current session to a new session id while maintaining all
     * session attributes.
     *
     * @api
     */
    public function migrate()
    {
        $this->storage->regenerate();
    }

    /**
     * Returns the session ID
     *
     * @return mixed  The session ID
     *
     * @api
     */
    public function getId()
    {
        if (false === $this->started) {
            $this->start();
        }

        return $this->storage->getId();
    }

    /**
     * Returns the locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Sets the locale.
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        if (false === $this->started) {
            $this->start();
        }

        $this->setPhpDefaultLocale($this->locale = $locale);
    }

    /**
     * Gets the flash messages.
     *
     * @return array
     */
    public function getFlashes()
    {
        return $this->flashes;
    }

    /**
     * Sets the flash messages.
     *
     * @param array $values
     */
    public function setFlashes($values)
    {
        if (false === $this->started) {
            $this->start();
        }

        $this->flashes = $values;
        $this->oldFlashes = array();
    }

    /**
     * Gets a flash message.
     *
     * @param string      $name
     * @param string|null $default
     *
     * @return string
     */
    public function getFlash($name, $default = null)
    {
        return array_key_exists($name, $this->flashes) ? $this->flashes[$name] : $default;
    }

    /**
     * Sets a flash message.
     *
     * @param string $name
     * @param string $value
     */
    public function setFlash($name, $value)
    {
        if (false === $this->started) {
            $this->start();
        }

        $this->flashes[$name] = $value;
        unset($this->oldFlashes[$name]);
    }

    /**
     * Checks whether a flash message exists.
     *
     * @param string $name
     *
     * @return Boolean
     */
    public function hasFlash($name)
    {
        if (false === $this->started) {
            $this->start();
        }

        return array_key_exists($name, $this->flashes);
    }

    /**
     * Removes a flash message.
     *
     * @param string $name
     */
    public function removeFlash($name)
    {
        if (false === $this->started) {
            $this->start();
        }

        unset($this->flashes[$name]);
    }

    /**
     * Removes the flash messages.
     */
    public function clearFlashes()
    {
        if (false === $this->started) {
            $this->start();
        }

        $this->flashes = array();
        $this->oldFlashes = array();
    }

    public function save()
    {
      if (1) return;
      
        if (false === $this->started) {
            $this->start();
        }

        $this->flashes = array_diff_key($this->flashes, $this->oldFlashes);

        $this->storage->write('_symfony2', array(
            'attributes' => $this->attributes,
            'flashes'    => $this->flashes,
            'locale'     => $this->locale,
        ));
    }

    public function __destruct()
    {
        if (true === $this->started) {
            $this->save();
        }
    }

    public function serialize()
    {
        return serialize(array($this->storage, $this->defaultLocale));
    }

    public function unserialize($serialized)
    {
        list($this->storage, $this->defaultLocale) = unserialize($serialized);
        $this->attributes = array();
        $this->started = false;
    }

    private function setPhpDefaultLocale($locale)
    {
        // if either the class Locale doesn't exist, or an exception is thrown when
        // setting the default locale, the intl module is not installed, and
        // the call can be ignored:
        try {
            if (class_exists('Locale', false)) {
                \Locale::setDefault($locale);
            }
        } catch (\Exception $e) {
        }
    }
}
