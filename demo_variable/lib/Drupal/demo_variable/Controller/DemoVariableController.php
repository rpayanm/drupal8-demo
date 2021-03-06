<?php

/**
 * @file
 * Contains \Drupal\demo_variable\Controller\DemoVariableController.
 */

namespace Drupal\demo_variable\Controller;

use Drupal\Component\Utility\Settings;
use Drupal\Core\Controller\ControllerBase;


/**
 * Returns responses for Events Demo module routes.
 */
class DemoVariableController extends ControllerBase {

  /**
   * Controller content callback: Lists the value of some variables called.
   *
   * @return string
   */
  public function infoPage() {

    $output['info'] = array(
      '#markup' => $this->t('This demonstrates the different variable systems in Drupal 8. The following examples are available:'),
    );

    $output['pages'] = array(
      '#theme' => 'item_list',
      '#items' => array(
        $this->t('Settings: !url.', array('!url' => l('/demo/variable/setting', 'demo/variable/setting'))),
        $this->t('State: !url.', array('!url' => l('/demo/variable/state', 'demo/variable/state'))),
        $this->t('Simple configuration: !url.', array('!url' => l('/demo/variable/config', 'demo/variable/config'))),
      ),
    );

    return $output;
  }

  /**
   * Controller content callback: Lists the values of some variables called.
   *
   * @return string
   */
  public function settingsPage() {

    $output['info'] = array(
      '#markup' => $this->t('Settings are set at bootstrap and can be overwritten in settings.php. These setting are currently available:'),
    );
    $items = array();
    foreach(Settings::getSingleton()->getAll() as $key => $value) {
      // We need to make some modifications to the value to display it properly.
      if (is_bool($value)) {
        $value = $value ? 'TRUE' : 'FALSE';
      }

      $items[] = format_string("\$settings['@name'] = @value", array('@name' => $key, '@value' => $value));

    }
    $output['settings'] = array(
      '#theme' => 'item_list',
      '#items' => $items,
    );

    return $output;
  }

  /**
   * Controller content callback: Show the time this page was last visited.
   *
   * @return string
   */
  public function statePage() {

    $output['info'] = array(
      '#prefix' => '<p>',
      '#markup' => $this->t('State variables represent a certain state of a process. For example the last time cron was run.'),
      '#suffix' => '</p>',
    );

    // Get the the Last Visited timestamp and prepare it for display.
    $last = \Drupal::state()->get('demo_variable.last_visited');
    $time = $last ? format_date($last) : $this->t('Unknown');

    // Set the Last Visited timestamp to the current time and store it.
    // @todo Don't hardcode the state service. Inject it as a depencency.
    \Drupal::state()->set('demo_variable.last_visited', REQUEST_TIME);

    $output['state'] = array(
      '#prefix' => '<p>',
      '#markup' => $this->t('Last time you visited this page: !time', array('!time' => $time)),
      '#suffix' => '</p>',
    );

    return $output;
  }

}
