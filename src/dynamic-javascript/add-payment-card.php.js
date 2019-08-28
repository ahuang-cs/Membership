/**
 * Add payment card code
 * Uses setup intents
 */

var stripe = Stripe(<?=json_encode(env('STRIPE_PUBLISHABLE'))?>);

var elements = stripe.elements({
  fonts: [
    {
      cssSrc: 'https://fonts.googleapis.com/css?family=Open+Sans',
    },
  ]
});

// Add an instance of the card Element into the `card-element` <div>.
//cardElement.mount('#card-element');

var cardNumberElement = elements.create('cardNumber', {
  style: stripeElementStyle
});
cardNumberElement.mount('#card-number-element');
var cardExpiryElement = elements.create('cardExpiry', {
  style: stripeElementStyle
});
cardExpiryElement.mount('#card-expiry-element');
var cardCvcElement = elements.create('cardCvc', {
  style: stripeElementStyle
});
cardCvcElement.mount('#card-cvc-element');

function cardChangeEvent(event, elementId) {
  var displayError = document.getElementById(elementId);
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
}

cardNumberElement.addEventListener('change', function(event) {
  cardChangeEvent(event, 'card-number-element-errors');
  if (event.brand) {
  	setCardBrandIcon(event.brand);
  }
});
cardExpiryElement.addEventListener('change', function(event) {
  cardChangeEvent(event, 'card-expiry-element-errors');
});
cardCvcElement.addEventListener('change', function(event) {
  cardChangeEvent(event, 'card-cvc-element-errors');
});

var cardholderName = document.getElementById('cardholder-name');
var cardholderAddress1 = document.getElementById('addr-line-1');
var cardholderZip = document.getElementById('addr-post-code');
cardholderZip.addEventListener('change', function(event) {
  //cardNumberElement.update({value: {postalCode: event.target.value.toUpperCase()}});
});
var cardholderCountry = document.getElementById('addr-country');
var cardButton = document.getElementById('card-button');
var clientSecret = cardButton.dataset.secret;

var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
  event.preventDefault();
  stripe.handleCardSetup(
    clientSecret, cardNumberElement, {
      payment_method_data: {
        billing_details: {
          name: cardholderName.value,
          address: {
            line1: cardholderAddress1.value,
            postal_code: cardholderZip.value,
            country: cardholderCountry.value,
          },
        }
      }
    }
  ).then(function(result) {
    var displayError = document.getElementById('card-errors');
    if (result.error) {
      // Display error.message in your UI.
      displayError.innerHTML = '<div class="alert alert-danger" id="card-errors-message"></div>'
      document.getElementById('card-errors-message').textContent = result.error.message;
    } else {
      // The setup has succeeded. Display a success message.
      disableButtons();
      displayError.innerHTML = '<div class="alert alert-success" id="card-errors-message"></div>'
      document.getElementById('card-errors-message').textContent = 'Card setup successfully. Please wait while we redirect you.';
      // The payment has succeeded. Display a success message.
      var form = document.getElementById('payment-form');
      // Submit the form
      form.submit();
    }
  });
});