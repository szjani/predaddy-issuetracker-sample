<?php

namespace DoctrineProxies\__CG__\predaddy\domain\impl\doctrine;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Aggregate extends \predaddy\domain\impl\doctrine\Aggregate implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', '' . "\0" . 'predaddy\\domain\\impl\\doctrine\\Aggregate' . "\0" . 'aggregateId', '' . "\0" . 'predaddy\\domain\\impl\\doctrine\\Aggregate' . "\0" . 'type', '' . "\0" . 'predaddy\\domain\\impl\\doctrine\\Aggregate' . "\0" . 'updated', '' . "\0" . 'predaddy\\domain\\impl\\doctrine\\Aggregate' . "\0" . 'version');
        }

        return array('__isInitialized__', '' . "\0" . 'predaddy\\domain\\impl\\doctrine\\Aggregate' . "\0" . 'aggregateId', '' . "\0" . 'predaddy\\domain\\impl\\doctrine\\Aggregate' . "\0" . 'type', '' . "\0" . 'predaddy\\domain\\impl\\doctrine\\Aggregate' . "\0" . 'updated', '' . "\0" . 'predaddy\\domain\\impl\\doctrine\\Aggregate' . "\0" . 'version');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Aggregate $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function createMetaEvent(\predaddy\domain\DomainEvent $event, $serializedEvent)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'createMetaEvent', array($event, $serializedEvent));

        return parent::createMetaEvent($event, $serializedEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function createSnapshot($serialized)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'createSnapshot', array($serialized));

        return parent::createSnapshot($serialized);
    }

    /**
     * {@inheritDoc}
     */
    public function updateSnapshot(\predaddy\domain\impl\doctrine\Snapshot $existing, $serialized)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'updateSnapshot', array($existing, $serialized));

        return parent::updateSnapshot($existing, $serialized);
    }

    /**
     * {@inheritDoc}
     */
    public function getAggregateId()
    {
        if ($this->__isInitialized__ === false) {
            return  parent::getAggregateId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAggregateId', array());

        return parent::getAggregateId();
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        if ($this->__isInitialized__ === false) {
            return  parent::getType();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getType', array());

        return parent::getType();
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVersion', array());

        return parent::getVersion();
    }

    /**
     * {@inheritDoc}
     */
    public function hashCode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hashCode', array());

        return parent::hashCode();
    }

    /**
     * {@inheritDoc}
     */
    public function equals(\precore\lang\ObjectInterface $object = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'equals', array($object));

        return parent::equals($object);
    }

    /**
     * {@inheritDoc}
     */
    public function toString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'toString', array());

        return parent::toString();
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, '__toString', array());

        return parent::__toString();
    }

}
