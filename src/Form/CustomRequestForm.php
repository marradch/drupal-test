<?php

namespace Drupal\my_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\my_module\Entity\CustomRequest;

/**
 * Implements an example form.
 */
class CustomRequestForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'test_custom_request_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
	  
	$form['full_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Full name'),
	  '#required' => TRUE,
    ];  
	
	$form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('E-mail'),
	  '#required' => TRUE,
    ];
	  	  
    $form['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Your phone number'),
	  '#required' => TRUE,
    ];
	
	$form['city'] = [
	  '#type' => 'select',
	  '#title' => $this
		->t('City'),
	  '#options' => [
		'' => '',
		'1' => $this
		  ->t('Kharkiv'),
		'2' => $this
		  ->t('Kyiv'),
	  ],
	  '#required' => TRUE,
	];
	
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('phone_number')) < 3) {
      $form_state->setErrorByName('phone_number', $this->t('The phone number is too short. Please enter a full phone number.'));
    }
	
	$query = \Drupal::entityQuery('custom_request')
		->condition('email', $form_state->getValue('email'));
		
	$result = $query->execute();
	
	if(!empty($result)){
		$form_state->setErrorByName('email', $this->t('The email already taken'));
	}	
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
	  
	$created = time();		
	$lc = LanguageInterface::LANGCODE_DEFAULT;
	$custom_request = new CustomRequest([	  
	  'created' => array($lc => $created),
	  'changed' => array($lc => $created),
	  'full_name' => array($lc => $form_state->getValue('full_name')),
	  'email' => array($lc => $form_state->getValue('email')),
	  'phone' => array($lc => $form_state->getValue('phone_number')),
	  'city' => array($lc => $form_state->getValue('city')),
	], 'custom_request');
	$custom_request->save();
	
	// mail admin
	$send_mail = new \Drupal\Core\Mail\Plugin\Mail\PhpMail(); // this is used to send HTML emails
	$from = \Drupal::config('system.site')->get('mail');
	$to = $from;
	$message['headers'] = array(
	'content-type' => 'text/plain',
	'MIME-Version' => '1.0',
	'reply-to' => $from,
	'from' => 'sender name <'.$from.'>'
	);
	$message['to'] = $to;
	$message['subject'] = "New custom request";

	$message['body'] = $this->t('Request number is @number', ['@number' => $custom_request->id()]);

	$send_mail->mail($message);
	
	// mail user
	$to = $form_state->getValue('email');
	$message['headers'] = array(
	'content-type' => 'text/html',
	'MIME-Version' => '1.0',
	'reply-to' => $from,
	'from' => 'sender name <'.$from.'>'
	);
	$message['to'] = $to;
	$message['subject'] = "You custom request saved";

	$message['body'] = $this->t('Request number is @number', ['@number' => $custom_request->id()]);

	$send_mail->mail($message);
		
    drupal_set_message($this->t('Your request number is @number', ['@number' => $custom_request->id()]));
  }

}