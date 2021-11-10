<div class="jumbotron enquiry" id="enquiry">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="judul-h3 white mb-5">
                    <h3>{{ __('pages.enquiry.title') }}</h3>
                </div>
                <div class="intro-footer white mb-5">
                    {{ __('pages.enquiry.description') }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-holder">
                    <form method="POST" action="{{ url('contact') }}" class="mb-3 was-vaidated ajax-request">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone <small id="telphelp class="form-text text-muted"><em>(Optional)</em></small></label>
                            <input type="number" name="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" name="message" required></textarea>
                        </div>
                        <input type="hidden" name="type" value="enquiry" />
                        <button type="submit" class="btn btn-success btn-block">Send</button>
                    </form>
                    <small>
                        <strong>Privacy:</strong> Yes, I have taken note of the <a href="privacy-policy.html">Privacy statement</a> and agree that the data provided by me may be collected and stored electronically for the purpose of processing and answering my enquiry. By sending the contact form I agree to the processing.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>