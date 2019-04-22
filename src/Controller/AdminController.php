<?
namespace Drupal\my_module\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * An example controller.
 */
class AdminController extends ControllerBase {

  /**
   * Returns a render-able array for a test page.
   */
  public function customRequestList() {
	 	
	$storage = \Drupal::entityManager()
		->getStorage('custom_request');
		
    $query = \Drupal::entityQuery('custom_request');
    $ids = $query
      ->sort('created', 'DESC')
      ->pager(10)
      ->execute();
    
    $items = $storage->loadMultiple($ids);

    $path = base_path();	  	

	$preparedItems = [];
	foreach ($items as $entity){
	  $preparedItems[] = [
		'id' => $entity->id(),
		'full_name' => $entity->get('full_name')->getString(),
		'email' => $entity->get('email')->getString(),
		'phone' => $entity->get('phone')->getString(),
		'city' => ['Kharkiv', 'Kyiv'][(int)$entity->get('city')->getString()-1],
		'created' => date('d.m.Y', $entity->get('created')->getString()),
	  ];
	}	
	  
	return [
		'results' => [
		  '#theme' => 'custom-request-list-theme',
		  '#items' => $preparedItems,
		  '#path'  => $path,
		],
		'pager' => [
		  '#type' => 'pager',
		],
	  ];    
  }

}