import '../bootstrap'

function getFields() {
    return $('#order-form').serializeArray().reduce(function (obj, item) {
        obj[item.name] = item.value;
        return obj;
    }, {})
}

function isEmptyFields() {
    const fields = getFields();

    return Object.values(fields).some((item) => {
        return item.length < 1;
    })
}

const headers = {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    'Accept': 'application/json',
    'Content-Type': 'application/json'
};

paypal.Buttons({
    onClick: function (data, actions) {
        if (isEmptyFields()) {
            alert('Please fill the form');
            return;
        }
    },

    // Call your server to set up the transaction
    createOrder: function (data, actions) {
        return fetch('/paypal/order/create/', {
            method: 'post',
            headers: headers,
            body: JSON.stringify(getFields())
        }).then(function (res) {
            return res.json();
        }).then(function (orderData) {
            console.log('orderData', orderData);
            return orderData.vendor_order_id;
        }).catch(function (error) {
            console.log('Error', error);
            return;
        });
    },

    // Call your server to finalize the transaction
    onApprove: function (data, actions) {
        return fetch('/paypal/order/' + data.orderID + '/capture/', {
            method: 'post',
            headers: headers
        }).then(function (res) {
            return res.json();
        }).then(function (orderData) {
            iziToast.success({
                title: 'Payment process was completed',
                position: 'topRight',
                onClosing: () => {
                    window.location.href = `/paypal/order/${orderData.id}/thankYou`
                }
            })
        }).catch(function (orderData) {
            var errorDetail = Array.isArray(orderData.details) && orderData.details[0];

            if (errorDetail && errorDetail.issue === 'INSTRUMENT_DECLINED') {
                return actions.restart(); // Recoverable state, per:
                // https://developer.paypal.com/docs/checkout/integration-features/funding-failure/
            }

            if (errorDetail) {
                var msg = 'Sorry, your transaction could not be processed.';
                if (errorDetail.description) msg += '\n\n' + errorDetail.description;
                if (orderData.debug_id) msg += ' (' + orderData.debug_id + ')';
                return alert(msg); // Show a failure message (try to avoid alerts in production environments)
            }

            // Successful capture! For demo purposes:
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
            var transaction = orderData.purchase_units[0].payments.captures[0];
            alert('Transaction ' + transaction.status + ': ' + transaction.id + '\n\nSee console for all available details');

            // Replace the above to show a success message within this page, e.g.
            // const element = document.getElementById('paypal-button-container');
            // element.innerHTML = '';
            // element.innerHTML = '<h3>Thank you for your payment!</h3>';
            // Or go to another URL:  actions.redirect('thank_you.html');
        });
    }

}).render('#paypal-button-container');
