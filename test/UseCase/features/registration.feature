Feature: Loyalty Card Registration

  Background:
    Given a registered customer

  Scenario: The customer cannot register an already registered card
    Given a card that is already registered by another customer
    When the customer registers the card
    Then it's impossible to register the card

  Scenario: The customer cannot register more than one card
    Given the customer has already registered a card
    When the customer registers another card number
    Then it's impossible to register another card

  Scenario: The customer can register an unregistered card
    Given the customer doesn't have a registered card
    When the customer registers an unregistered card
    Then the card is registered and associated to the customer

  Scenario: The customer can delete a registered card
    Given the customer has a registered card
    When the customer deletes the card
    Then the card is deleted
