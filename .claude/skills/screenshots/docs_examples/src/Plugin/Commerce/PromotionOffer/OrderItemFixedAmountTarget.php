<?php

namespace Drupal\docs_examples\Plugin\Commerce\PromotionOffer;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\commerce_order\Adjustment;
use Drupal\commerce_promotion\Attribute\CommercePromotionOffer;
use Drupal\commerce_promotion\Entity\PromotionInterface;
use Drupal\commerce_promotion\Plugin\Commerce\PromotionOffer\FixedAmountOffTrait;
use Drupal\commerce_promotion\Plugin\Commerce\PromotionOffer\OrderItemPromotionOfferBase;

/**
 * developer-guide/promotions/offers.md: "A simple example".
 */
#[CommercePromotionOffer(
  id: "my_module_fixed_amount_target",
  label: new TranslatableMarkup("Discount each matching product to fixed amount"),
  entity_type: "commerce_order_item",
)]
class OrderItemFixedAmountTarget extends OrderItemPromotionOfferBase {

  use FixedAmountOffTrait;

  /**
   * {@inheritdoc}
   */
  public function apply(EntityInterface $entity, PromotionInterface $promotion) {
    $this->assertEntity($entity);
    /** @var \Drupal\commerce_order\Entity\OrderItemInterface $order_item */
    $order_item = $entity;
    $unit_price = $order_item->getUnitPrice();
    $target_amount = $this->getAmount();
    if ($unit_price->getCurrencyCode() != $target_amount->getCurrencyCode()) {
      return;
    }
    // Don't raise the order item unit price to a higher target value.
    if ($target_amount->greaterThan($unit_price)) {
      return;
    }

    // Adjust unit price to the target amount.
    $adjustment_amount = $unit_price->subtract($target_amount);

    $order_item->addAdjustment(new Adjustment([
      'type' => 'promotion',
      'label' => $promotion->getDisplayName() ?: $this->t('Discount'),
      'amount' => $adjustment_amount->multiply('-1'),
      'source_id' => $promotion->id(),
    ]));
  }

}
