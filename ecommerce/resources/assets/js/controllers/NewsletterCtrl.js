let NewsletterCtrl = {

    init: () => {
        // Cadastro do email na newsletter
        NewsletterCtrl.onSubmitFormNewsletter();
    },

    // Cadastro do email na newsletter
    onSubmitFormNewsletter() {
        let $form = $("form[name='newsletter']");
        let $loader = $form.find(".spinner-border");
        let $result = $form.find("#result-newsletter");
        $form.on('submit', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            $loader.removeClass('d-none');
            let result = $.post($this.attr('action'), $this.serialize());
            result.done((response) => {
                $loader.addClass('d-none');
                $result.removeClass('d-none');
                $result.text("Seu e-mail foi cadastrado para receber as novidades!");
                $this.find("input#newsletter_input").val("");
            });
            result.catch(() => {
                $loader.addClass('d-none');
                $result.removeClass('d-none');
                $result.text("Não foi possível cadastrar seu email!");
            })
        });
    }
};
