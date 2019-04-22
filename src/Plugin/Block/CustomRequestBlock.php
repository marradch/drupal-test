<?php 

namespace Drupal\my_module\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Provides a 'Custom Request block' .
 *
 * @Block(
 *   id = "test_custom_request_block",
 *   admin_label = @Translation("Custom Request block"),
 *   category = @Translation("Test task blocks")
 * )
 */
 class CustomRequestBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
  	
	/*$block = [
		'#theme' => 'custom-request-block-theme',
		'#description' => t('Description'),
	];	
	
    return $block;*/
	
	$form = \Drupal::formBuilder()->getForm('Drupal\my_module\Form\CustomRequestForm');
	
	return $form;
	
  }
  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    if (!$account->isAnonymous() ) {
      return AccessResult::allowed()->addCacheContexts(['route.name']);
    }
    return AccessResult::forbidden(); 
  }
}