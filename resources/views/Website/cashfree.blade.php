
        <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
 
        <div class="row">
            <p>Click below to open the checkout page in current tab</p>
            <button id="renderBtn">Pay Now</button>
        </div>
        <script>
            const cashfree = Cashfree({
                mode: "sandbox",
            });
            document.getElementById("renderBtn").addEventListener("click", () => {
                let checkoutOptions = {
                    paymentSessionId: "session_9ekeQdaQtmC-hsPAKHhj4VEjqeUOkydXbTCfhq5eU005nZCQgeI39siIYWLOxJrOMV4tPPjJuWBn4NXJZV4l76Ljo1FQZqq83qYaGbC_ZZ4r60tmpJtUL6Mw1IaIZgpaymentpayment",
                    redirectTarget: "_self",
                };
                cashfree.checkout(checkoutOptions);
            });
        </script>
    