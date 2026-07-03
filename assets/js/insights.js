document.addEventListener('DOMContentLoaded', function() {
    const subscribeForm = document.querySelector('.te-subscribe-form');
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            const company = this.querySelector('input[name="company"]').value;
            
            // For now, just log and show a success message
            // Future sprint will connect to REST API and Zoho
            console.log('Subscription request:', { email, company });
            
            const container = this.parentElement;
            container.innerHTML = '<h4>Thank you for subscribing!</h4><p>You will now receive our latest enterprise insights.</p>';
        });
    }
});
