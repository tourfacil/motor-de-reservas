let Facebook = {

    /** Verifica se existe a variavel do Facebook */
    _verifyFb: () => {
        return (typeof fbq !== "undefined");
    },

    /** Envia a visualização do servico para o Facebook */
    sendViewContent: (uuid, price) => {
        if(Facebook._verifyFb()) {
            setTimeout(() => {
                fbq('track', 'ViewContent', {
                    contents: [{ id: uuid, quantity: 1 }],
                    content_type: 'product',
                    value: price,
                    currency: 'BRL',
                })
            }, 600);
        }
    },

    /** Evento de servico adicionado ao carrinho */
    sendAddToCard: (uuid, price) => {
        if(Facebook._verifyFb()) {
            fbq('track', 'AddToCart', {
                contents: [{ id: uuid, quantity: 1 }],
                content_type: 'product',
                value: price,
                currency: 'BRL',
            })
        }
    },

    /** Checkout */
    sendStepCheckout: (products, total_price) => {
        setTimeout(() => {
            if(Facebook._verifyFb() && products.length) {
                fbq('track', 'InitiateCheckout', {
                    contents: products,
                    content_type: 'product',
                    num_items: products.length,
                    value: total_price,
                    currency: 'BRL',
                })
            }
        }, 600);
    },

    /** AddPaymentInfo */
    sendAddPaymentInfo: (products, total_price) => {
        setTimeout(() => {
            if(Facebook._verifyFb() && products.length) {
                fbq('track', 'AddPaymentInfo', {
                    contents: products,
                    content_type: 'product',
                    num_items: products.length,
                    value: total_price,
                    currency: 'BRL',
                })
            }
        }, 600);
    },

    /** Purchase */
    sendPurchase: (products, total_price) => {
        setTimeout(() => {
            if (Facebook._verifyFb() && products.length) {
                fbq('track', 'Purchase', {
                    contents: products,
                    content_type: 'product',
                    value: total_price,
                    currency: 'BRL',
                })
            }
        }, 600);
    }
};


module.exports = Facebook;
