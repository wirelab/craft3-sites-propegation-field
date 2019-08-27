<?php

namespace wirelab\sitespropagationfield\listeners;

use craft\elements\Entry;
use craft\events\ModelEvent;
use yii\base\Event;
use wirelab\sitespropagationfield\fields\SitesField;

class EntrySavedListener {

    /**
     * @var Entry
     */
    public $event;

    /**
     * @var null
     */
    private $sitesField = null;

    /**
     * EntrySavedListener constructor.
     *
     * @param Event $event
     */
    public function __construct(ModelEvent $event)
    {
        $this->event = $event;
        $this->setFields();

        if(
            $this->sitesField
            && is_array($this->event->sender->getFieldValue($this->sitesField))
        ) {
            $this->propegate();
        }
    }

    /**
     * Finds fields that need to be propegated and saves them
     * @note this will only work for one field that has propegation set
     *
     * @return void
     */
    public function setFields() : void
    {
        $fields = $this->event->sender->getFieldLayout()->getFields();

        foreach($fields as $field) {
            if(
                get_class($field) === SitesField::class
                && $field->propagate
            ) {
                $this->sitesField = $field->handle;
                break;
            }
        }
    }

    /**
     *  Will set the proper settings if the site is selected
     */
    public function propegate() : void
    {
        $this->event->sender->enabledForSite = in_array(
            $this->event->sender->siteId,
            $this->event->sender->getFieldValue($this->sitesField)
        );
    }

}