class modal {
static crt(mId, title, body, actions) {
$(`#${mId}`).remove();
const modalContainer = `
<div class="modal fade custom-modal" tabindex="-1" role="dialog" id="${mId}" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="gridSystemModalLabel">
                    ${title}
                </h4>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                ${body}
            </div>
            <div class="modal-footer">
                ${actions}
            </div>
        </div>
    </div>
</div>
`;
// Append modal and show
$('body').append(modalContainer);
$(`#${mId}`).modal('show');
}
}