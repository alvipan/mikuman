<div id="confirm-modal" class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 modal-middle hidden bg-black/50" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-md font-semibold title text-lg"></h3>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-soft" data-overlay="#confirm-modal" wire:loading.class="disabled">Cancel</button>
                <button type="button" class="btn btn-sm btn-error btn-confirm" wire:loading.class="btn-disabled">Confirm</button>
            </div>
        </div>
    </div>
</div>