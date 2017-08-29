<?php
/**
 * Entries of Section plugin for Craft CMS
 *
 * Craft field type plugin that limits listed entries by the current entries section
 *
 * @author    saschame
 * @copyright Copyright (c) 2017 saschame
 * @link      https://github.com/saschame
 * @package   EntriesOfSection
 * @since     1.0.0
 */

namespace Craft;

class EntriesOfSectionFieldType extends BaseElementFieldType
{

    protected $elementType = 'Entry';
    /**
     * Returns the name of the fieldtype.
     *
     * @return mixed
     */
    public function getName()
    {
        return Craft::t('Entries of Section');
    }

    public function getInputTemplateVariables($name, $criteria)
    {
        if (
            $this->elementType === 'Entry' &&
            $this->element &&
            !empty($this->element->elementType) &&
            $this->element->elementType === 'Entry'
        ) {
            $variables = parent::getInputTemplateVariables($name, $criteria);

            $element = $this->element;
            $section = $element->getSection();
            if ($section) {
                $variables['criteria']['sectionId'][] = $section->id;
                $variables['sources'] = ['section:' . $section->id];
            }

            return $variables;
        } else if (
            $this->elementType === 'Entry' &&
            $this->element &&
            !empty($this->element->elementType) &&
            $this->element->elementType === 'SuperTable_Block'
        ) {
            $handle = $this->getClassHandle();
            $owner_id = $this->element->getAttribute('ownerId');
            $owner = $this->element->getOwner();

            if ($handle === 'EntriesOfSection' && $owner) {
                $section = $owner->getSection();
                if ($section) {
                    $settings = $this->getSettings();
                    $settings->setAttribute('sources', ['section:' . $section->id]);
                    $this->setSettings($settings);
                }
            }
            $variables = parent::getInputTemplateVariables($name, $criteria);
            $variables['sourceElementId'] = $owner_id;

            return $variables;
        } else {
            $variables = parent::getInputTemplateVariables($name, $criteria);

            return $variables;
        }
    }
}
