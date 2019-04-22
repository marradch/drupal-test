<?php

namespace Drupal\my_module\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Url;

/**
* Defines the CustomRequest entity.
*
* @ingroup custom_request
*
* @ContentEntityType(
*   id = "custom_request",
*   label = @Translation("Custom request"),
*   base_table = "custom_request",
*   entity_keys = {
*     "id" = "id",
*   },
* )
*/
class CustomRequest extends ContentEntityBase implements ContentEntityInterface {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of entity.'))
      ->setReadOnly(TRUE);
	 
    $fields['full_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Full Name'))
      ->setDescription(t('Full Name.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 150,
        'text_processing' => 0,
      ));  
	  
	$fields['email'] = BaseFieldDefinition::create('string')
      ->setLabel(t('E-mail'))
      ->setDescription(t('E-mail'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 150,
        'text_processing' => 0,
      ));   
	  
	$fields['phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Phone number'))
      ->setDescription(t('Phone number'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 20,
        'text_processing' => 0,
      ));	
	
    $fields['city'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('City'))
      ->setDescription(t('City'));
    
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that request was created.'));
	    
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that request was Changed.'));

    return $fields;
  }
}