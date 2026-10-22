<?php

namespace Drupal\docs_examples\Plugin\Commerce\PromotionOffer;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\commerce_order\Adjustment;
use Drupal\commerce_price\Price;
use Drupal\commerce_promotion\Attribute\CommercePromotionOffer;
use Drupal\commerce_promotion\Entity\PromotionInterface;
use Drupal\commerce_promotion\Plugin\Commerce\PromotionOffer\OrderItemPromotionOfferBase;

/**
 * developer-guide/promotions/offers.md: "Slightly more advanced example".
 *
 * Discounts a set quantity of items to fixed amount.
 */
#[CommercePromotionOffer(
  id: "my_module_discount_to_fixed",
  label: new TranslatableMarkup("Discount items of each matching product to fixed amount"),
  entity_type: "commerce_order_item",
)]
class OrderItemDiscountToFixed extends OrderItemPromotionOfferBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'quantity' => 1,
      'amount' => NULL,
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $form['quantity'] = [
      '#type' => 'number',
      '#title' => $this->t('Quantity'),
      '#description' => $this->t('Number of items to discount for each matching product.'),
      '#default_value' => $this->configuration['quantity'],
      '#min' => 1,
      '#required' => TRUE,
      '#weight' => -2,
    ];
    $form['amount'] = [
      '#type' => 'commerce_price',
      '#title' => $this->t('Amount'),
      '#default_value' => $this->configuration['amount'],
      '#required' => TRUE,
      '#weight' => -1,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    if (!$form_state->getErrors()) {
      $values = $form_state->getValue($form['#parents']);
      $this->configuration['quantity'] = $values['quantity'];
      $this->configuration['amount'] = $values['amount'];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function apply(EntityInterface $entity, PromotionInterface $promotion) {
    $this->assertEntity($entity);
    /** @var \Drupal\commerce_order\Entity\OrderItemInterface $order_item */
    $order_item = $entity;
    $unit_price = $order_item->getUnitPrice();
    $target_amount = Price::fromArray($this->configuration['amount']);
    if ($unit_price->getCurrencyCode() != $target_amount->getCurrencyCode() || $target_amount->greaterThan($unit_price)) {
      return;
    }
    // Calculate per-item reduction amount.
    $adjustment_amount = $unit_price->subtract($target_amount);
    $quantity = min($this->configuration['quantity'], $order_item->getQuantity());

    $order_item->addAdjustment(new Adjustment([
      'type' => 'promotion',
      'label' => $promotion->getDisplayName() ?: $this->t('Discount'),
      'amount' => $adjustment_amount->multiply((string) -$quantity),
      'source_id' => $promotion->id(),
    ]));
  }

}
