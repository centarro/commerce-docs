<?php

namespace Drupal\docs_examples\Plugin\Commerce\PaymentGateway;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\Attribute\CommercePaymentGateway;
use Drupal\commerce_payment\Exception\PaymentGatewayException;
use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayBase;
use Drupal\docs_examples\PluginForm\RedirectCheckoutForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * The QuickPay gateway of the "Creating a payment gateway" page.
 *
 * Its offsite form redirects to the example module's dummy page, and every
 * return fails, for the payment error message shot.
 */
#[CommercePaymentGateway(
  id: "quickpay_redirect_checkout",
  label: new TranslatableMarkup("QuickPay (Redirect to quickpay)"),
  display_label: new TranslatableMarkup("QuickPay"),
  forms: [
    "offsite-payment" => RedirectCheckoutForm::class,
  ],
  payment_method_types: ["credit_card"],
  credit_card_types: ["mastercard", "visa"],
)]
class RedirectCheckout extends OffsitePaymentGatewayBase {

  public function defaultConfiguration() {
    return [
      'private_key' => '',
      'api_key' => '',
    ] + parent::defaultConfiguration();
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['private_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Private key'),
      '#description' => $this->t('This is the private key from the Quickpay manager.'),
      '#default_value' => $this->configuration['private_key'],
      '#required' => TRUE,
    ];

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API key'),
      '#description' => $this->t('The API key for the same user as used in Agreement ID.'),
      '#default_value' => $this->configuration['api_key'],
      '#required' => TRUE,
    ];

    return $form;
  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $values = $form_state->getValue($form['#parents']);
    $this->configuration['private_key'] = $values['private_key'];
    $this->configuration['api_key'] = $values['api_key'];
  }

  public function onReturn(OrderInterface $order, Request $request) {
    throw new PaymentGatewayException('The payment was declined.');
  }

}
