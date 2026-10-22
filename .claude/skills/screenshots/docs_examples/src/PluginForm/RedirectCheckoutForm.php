<?php

namespace Drupal\docs_examples\PluginForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm;

/**
 * Redirects to the example module's dummy payment page.
 */
class RedirectCheckoutForm extends PaymentOffsiteForm {

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $data = [
      'return' => $form['#return_url'],
      'cancel' => $form['#cancel_url'],
      'total' => $this->entity->getAmount()->getNumber(),
    ];
    $redirect_url = Url::fromRoute('commerce_payment_example.dummy_redirect_post')->toString();
    return $this->buildRedirectForm($form, $form_state, $redirect_url, $data, self::REDIRECT_POST);
  }

}
