<?php
/**
 * Sites Field plugin for Craft 3.0
 * @copyright Copyright East Slope Studio, LLC
 */

namespace wirelab\sitespropagationfield;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\elements\Entry;
use craft\services\Elements;
use craft\services\Fields;

use wirelab\sitespropagationfield\fields\SitesField;

use wirelab\sitespropagationfield\listeners\EntrySavedListener;
use yii\base\Event;

/**
 * The main Craft plugin class.
 */
class Sites extends Plugin
{

	/**
	 * @inheritdoc
	 * @see craft\base\Plugin
	 */
	public function init()
	{
		parent::init();

		Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, [$this, 'registerFieldTypes']);

        Event::on(Entry::class, Entry::EVENT_BEFORE_SAVE, function (Event $event) {
            new EntrySavedListener($event);
        });


	}

	/**
	 * Registers the field type provided by this plugin.
	 * @param RegisterComponentTypesEvent $event The event.
	 * @return void
	 */
	public function registerFieldTypes(RegisterComponentTypesEvent $event)
	{
		$event->types[] = SitesField::class;
	}
}
