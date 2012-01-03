<?php
namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\NotifyPropertyChanged,
    Doctrine\Common\PropertyChangedListener;

use Zayso\CoreBundle\Component\Debug;

/* ============================================================
 * Implements property changes stuff
 * and blob handling
 * ORM\ChangeTrackingPolicy("NOTIFY")
 */
class BaseEntity implements NotifyPropertyChanged
{
    /* ========================================================
     * Blob routines
     * Assume mostly readonly
     */
    /**  ORM\Column(type="text", name="datax", nullable=true) */
    protected $datax = null;
    protected $data  = null;
    
    public function get($name, $default = null)
    {
        // First time called after loading
        if (!$this->data) $this->data = unserialize($this->datax);

        if (isset($this->data[$name])) return $this->data[$name];
        return $default;
    }
    public function set($name,$value)
    {
        // First time called after loading
        if (!$this->data) $this->data = unserialize($this->datax);
        
        // Special for unsetting
        if ($value === null)
        {
            // Do nothing if have nothing
            if (!isset($this->data[$name])) return;
            
            $this->onPropertyChanged($name,$this->data[$name],$value);
            
            unset($this->data[$name]);
            $this->datax = serialize($this->data);
 
            return;
        }
        
        // Only if changed
        if (isset($this->data[$name])) 
        {
            $oldValue = $this->data[$name];
            if ($oldValue == $value) return;
        }
        else $oldValue = null;
        
        $this->onPropertyChanged($name,$oldValue,$value);
        
        $this->data[$name] = $value;
        $this->datax = serialize($this->data);
    }
    /* ========================================================================
     * Property change stuff
     */
    protected $listeners = array();
    protected $changed   = false;
    
    public function addPropertyChangedListener(PropertyChangedListener $listener)
    {
        $this->listeners[] = $listener;
    }
    public function isChanged() { return $changed; }
    
    protected function onPropertyChanged($propName, $oldValue, $newValue)
    {
        $this->changed = true;
        foreach ($this->listeners as $listener) 
        {
            $listener->propertyChanged($this, $propName, $oldValue, $newValue);
        }
    }
    protected function onObjectPropertySet($name,$newObject)
    {
        $oldObject = $this->$name;
        
        if ($oldObject && $newObject)
        {
            if ($oldObject->getId() == $newObject->getId()) return;
        }
        $this->onPropertyChanged($name,$oldObject,$newObject);
        
        $this->$name = $newObject;
    }
    protected function onScalerPropertySet($name,$value)
    {
        if ($this->$name === $value) return;
        $this->onPropertyChanged($name,$this->$name,$value);
        $this->$name = $value;
    }
}
?>
