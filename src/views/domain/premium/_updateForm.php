<div class="well well-sm">

    <div class="row">
        <div class="col-md-12">
            <button type="button" class="close pf-update-form-close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <?= $this->render($formFileName, compact('model', 'domain', 'forwardingOptions')) ?>

</div>

