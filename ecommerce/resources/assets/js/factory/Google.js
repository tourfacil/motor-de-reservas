let Google = {
    /** Verifica se existe a variavel do DataLayer */
    _verifyDataLayer: () => {
        return (typeof dataLayer !== "undefined");
    },

    /**
     * Envia os dados da compra
     *
     * @param pedido
     * @param valor
     * @param servicos
     * @param cliente_email
     *
     */
    sendPurchaseGtm: (pedido, valor, servicos, cliente_email) => {

        if(Google._verifyDataLayer() && servicos.length > 0) {

            console.log("Registrado pedido no Google Tag Manager");

            dataLayer.push({
                'event': 'purchase',
                'ecommerce': {
                    'currencyCode': 'BRL',
                    'purchase': {
                        'actionField': {
                            'id': pedido,
                            'revenue': valor,
                            'cliente_email': cliente_email,
                        },
                        'products': servicos
                    }
                }
            });
        }
    },

    /**
     * Send event GA
     *
     * @param category
     * @param action
     */
    sendEventGtm: (category, action) => {
        if(Google._verifyDataLayer()) {
            dataLayer.push({
                "event_category": category,
                "event_action": action,
                "event": "custom_tracking"
            });
        }
    },

    /**
     * Google Measuring Products Impressions
     *
     * @param products
     */
    sendProductsImpressions: (products) => {
        if(Google._verifyDataLayer() && products.length) {
            setTimeout(() => {
                dataLayer.push({
                    "event": "productImpressions",
                    "ecommerce": {
                        "currencyCode": "BRL",
                        "impressions": products
                    }
                });
            }, 600);
        }
    },

    /**
     * Google Measuring Product Clicks
     *
     * @param product
     * @param list
     */
    sendProductClicks: (product, list) => {
        if(Google._verifyDataLayer()) {
            dataLayer.push({
                'event': 'productClick',
                'ecommerce': {
                    'click': {
                        'actionField': {'list': list},
                        'products': [product]
                    }
                }
            });
        }
    },

    /**
     * Measuring Views of Product Details
     *
     * @param product
     * @param list
     */
    sendProductDetails: (product, list) => {
        if(Google._verifyDataLayer()) {
            setTimeout(() => {
                dataLayer.push({
                    "event": "productDetails",
                    'ecommerce': {
                        'detail': {
                            'products': [product]
                        }
                    }
                });
            }, 600);
        }
    },

    /**
     * Measuring Additions a Shopping Cart
     *
     * @param product
     */
    sendAddToCard: (product) => {
        if(Google._verifyDataLayer()) {
            dataLayer.push({
                "event": "addToCart",
                "ecommerce": {
                    "currencyCode": "BRL",
                    "add": {"products": [product]}
                }
            });
        }
    },

    /**
     * Removing a Product from a Shopping Cart
     *
     * @param product
     */
    sendRemoveFromCart: (product) => {
        if(Google._verifyDataLayer()) {
            dataLayer.push({
                'event': 'removeFromCart',
                'ecommerce': {
                    'remove': { 'products': [product] }
                }
            });
        }
    },

    /**
     * Measuring a Checkout
     *
     * @param products
     * @param step
     */
    sendStepCheckout: (products, step) => {
        if(Google._verifyDataLayer() && products.length) {
            setTimeout(() => {
                dataLayer.push({
                    'event': 'checkout',
                    'ecommerce': {
                        'checkout': {
                            'actionField': {'step': step},
                            'products': products
                        }
                    }
                });
            }, 600);
        }
    }
};

module.exports = Google;
